<?php

namespace App\Http\Controllers\Customer;

use App\Events\DashboardStatesUpdateEvent;
use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedEmail;
use App\Mail\PointsCreditEmail;
use App\Models\AdminWallet;
use App\Models\AdminWalletLog;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CustomerPointLog;
use App\Models\Event;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PlayArea;
use App\Models\Point;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBank;
use App\Models\VendorBankLog;
use Berkayk\OneSignal\OneSignalFacade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
use App\Http\Controllers\Customer\AppController as CustomerAppController;


class CheckoutController extends Controller
{
    protected $getCustomerAppControllerData;

    public function __construct(CustomerAppController $customerAppController)
    {
        $this->getCustomerAppControllerData = $customerAppController;
    }

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $sessionFoodItems = $request->session()->get('cart.items', []);
        $sessionPlayAreas = $request->session()->get('cart.playAreas', []);
        $sessionEvents = $request->session()->get('cart.events', []);

        if ($request->guest) {
            $user->cartItems()->delete();
        }
        foreach ($sessionFoodItems as $itemId => $quantity) {
            /** @var \App\Models\User $user */
            $user->cartItems()->updateOrCreate(
                ['item_id' => $itemId],
                ['quantity' => $quantity]
            );
        }

        foreach ($sessionPlayAreas as $playAreaId => $data) {
            /** @var \App\Models\User $user */
            $user->cartItems()->updateOrCreate(
                ['play_area_id' => $playAreaId],
                [
                    'quantity' => $data['playersCount'],
                    'play_area_date' => $data['date'] ?? null,
                    'play_area_start_time' => $data['start_time'] ?? null,
                    'play_area_end_time' => $data['end_time'] ?? null,
                ]
            );
        }

        foreach ($sessionEvents as $eventId => $quantity) {
            /** @var \App\Models\User $user */
            $user->cartItems()->updateOrCreate(
                ['event_id' => $eventId],
                ['quantity' => $quantity],
            );
        }

        $activeFoodItemIds = Item::where('status', 1)
            ->where('approve', 1)
            ->pluck('id');
        $activePlayAreaIds = PlayArea::where('status', 1)
            ->pluck('id');
        $activeEventIds = Event::where('status', 1)
            ->pluck('id');

        Cart::where('user_id', auth()->id())
            ->where(function ($query) use ($activeFoodItemIds, $activePlayAreaIds, $activeEventIds) {
                $query->where(function ($query) use ($activeFoodItemIds) {
                    $query->whereNotNull('item_id')
                        ->whereNotIn('item_id', $activeFoodItemIds);
                })
                    ->orWhere(function ($query) use ($activePlayAreaIds) {
                        $query->whereNotNull('play_area_id')
                            ->whereNotIn('play_area_id', $activePlayAreaIds);
                    })
                    ->orWhere(function ($query) use ($activeEventIds) {
                        $query->whereNotNull('event_id')
                            ->whereNotIn('event_id', $activeEventIds);
                    });
            })
            ->delete();

        $cartItems = Cart::where('user_id', auth()->id())->where('event_id', null)->where('play_area_id', null)->with(['item.vendor'])->get();

        $cartEvents = Cart::where('user_id', auth()->id())->where('item_id', null)->where('play_area_id', null)->with(['event'])->get();

        foreach ($cartEvents as $event) {
            $eventValidation = $this->getCustomerAppControllerData->validateEvent($event->event->id, $event->quantity);
            $eventValid = $eventValidation['success'] ?? true;
            if (!$eventValid) {
                return response()->json($eventValidation);
            }
        }

        $cartPlayAreas = Cart::where('user_id', auth()->id())->where('item_id', null)->where('event_id', null)->with(['playArea'])->get();

        $totalItemCost = $cartItems->sum(function ($item) {
            return $item->quantity * $item->item->price;
        });

        $totalPointsNotApplicableItemCost = $cartItems->sum(function ($item) {
            if (!$item->item->points_status) {
                return $item->quantity * $item->item->price;
            }
        });

        $totalEventCost = $cartEvents->sum(function ($event) {
            return $event->quantity * $event->event->price;
        });

        try {
            $totalPlayAreaCost = $cartPlayAreas->sum(function ($playArea) {
                $date = $playArea->play_area_date;
                $start = isset($playArea->play_area_start_time) ? Carbon::parse($playArea->play_area_start_time) : null;
                $end = isset($playArea->play_area_end_time) ? Carbon::parse($playArea->play_area_end_time) : null;

                if (!$start || !$end || !$date) {
                    throw new \Exception('Invalid Play Area');
                }

                $durationInMinutes = $start->diffInMinutes($end);
                $durationInHours = $durationInMinutes / 60;

                return $playArea->quantity * $durationInHours * $playArea->playArea->price;
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops! Booking Play Area Failed.']);
        }

        foreach ($cartPlayAreas as $cartPlayArea) {
            $cartPlayAreaValidation = $this->getCustomerAppControllerData->validatePlayArea($cartPlayArea->play_area_id, $cartPlayArea->play_area_date, $cartPlayArea->play_area_start_time,  $cartPlayArea->play_area_end_time, $cartPlayArea->quantity);
            $playAreaValid = $cartPlayAreaValidation['success'] ?? true;
            if (!$playAreaValid) {
                return response()->json($cartPlayAreaValidation);
            }
        }

        $totalProductPrice = $totalPlayAreaCost + $totalEventCost + $totalItemCost;
        $totalProductCount = count($cartItems) + count($cartPlayAreas) + count($cartEvents);
        $totalProductPriceAfterDiscount = $totalProductPrice;

        $coupon = Coupon::where('id', $request->coupon)->where('status', 1)->first();
        $coupon_discount = 0;
        $point_discount = $request->points * Point::first()->value;
        $couponCategoryIds = [];
        $couponItemIds = [];
        $totalApplicableItemsPrice = 0;
        $totalApplicableItemsPriceAfterDiscount = 0;
        if ($coupon) {
            $couponValidation = $this->getCustomerAppControllerData->validateCoupon($coupon, $totalProductPrice);
            $couponCategoryIds = $coupon->itemCategories->pluck('id')->toArray();
            $couponItemIds = $coupon->items->pluck('id')->toArray();
            $isCouponValid = $couponValidation['coupon_success'] ?? true;
            if (!$isCouponValid) {
                return response()->json(['success' => false, 'message' => 'Oops! Invalid Coupon.']);
            } else {
                if (!empty($couponCategoryIds) && !empty($couponItemIds)) {
                    foreach ($cartItems as $cartItem) {
                        $cartItemCategoryId = $cartItem->item->category_id;
                        $cartItemId = $cartItem->item->id;
                        if (in_array($cartItemCategoryId, $couponCategoryIds) && in_array($cartItemId, $couponItemIds)) {
                            $totalApplicableItemsPrice += $cartItem->item->price * $cartItem->quantity;
                        }
                    }
                } elseif (!empty($couponCategoryIds) && empty($couponItemIds)) {
                    foreach ($cartItems as $cartItem) {
                        $cartItemCategoryId = $cartItem->item->category_id;
                        if (in_array($cartItemCategoryId, $couponCategoryIds)) {
                            $totalApplicableItemsPrice += $cartItem->item->price * $cartItem->quantity;
                        }
                    }
                } elseif (empty($couponCategoryIds) && !empty($couponItemIds)) {
                    foreach ($cartItems as $cartItem) {
                        $cartItemId = $cartItem->item->id;
                        if (in_array($cartItemId, $couponItemIds)) {
                            $totalApplicableItemsPrice += $cartItem->item->price * $cartItem->quantity;
                        }
                    }
                }

                if ($totalApplicableItemsPrice > 0) {
                    if ($coupon->discount_type == 'fixed') {
                        $coupon_discount = min($coupon->discount, $totalApplicableItemsPrice);
                    } elseif ($coupon->discount_type == 'percentage') {
                        $coupon_discount = ($coupon->discount / 100) * $totalApplicableItemsPrice;
                        if ($coupon->maximum_amount && $coupon_discount > $coupon->maximum_amount) {
                            $coupon_discount = $coupon->maximum_amount;
                        }
                    }
                    $totalProductPriceAfterDiscount -= $coupon_discount;
                    $totalApplicableItemsPriceAfterDiscount = $totalApplicableItemsPrice - $coupon_discount;
                } else {
                    if ($coupon->discount_type == 'fixed') {
                        $coupon_discount = $coupon->discount;
                        $totalProductPriceAfterDiscount -= $coupon_discount;
                    } elseif ($coupon->discount_type == 'percentage') {
                        $coupon_discount = ($coupon->discount / 100) * $totalProductPriceAfterDiscount;
                        if ($coupon->maximum_amount && $coupon_discount > $coupon->maximum_amount) {
                            $coupon_discount = $coupon->maximum_amount;
                        }
                        $totalProductPriceAfterDiscount -= $coupon_discount;
                    }
                }
            }
        }
        if ($request->points > 0) {
            $pointValidation = $this->getCustomerAppControllerData->validatePoint($request->points, $totalProductCount, $totalEventCost, $totalPlayAreaCost);
            $isPointApplicable = $pointValidation['point_success'] ?? true;
            if (!$isPointApplicable) {
                return response()->json(['success' => false, 'message' => 'Oops! Cant use points.']);
            } else {
                $totalProductPriceAfterDiscount = $totalProductPriceAfterDiscount - $point_discount; // 690
            }
        }

        $settings = Setting::first();

        $gstCost = 0;
        $sgtCost = 0;
        $serviceTaxCost = 0;

        if ($settings->gst > 0) {
            $gstCost = $totalProductPriceAfterDiscount * ($settings->gst / 100);
        }
        if ($settings->sgt > 0) {
            $sgtCost = $totalProductPriceAfterDiscount * ($settings->sgt / 100);
        }
        if ($settings->service_tax > 0) {
            $serviceTaxCost = $totalProductPriceAfterDiscount * ($settings->service_tax / 100);
        }

        $netTotal = $totalProductPriceAfterDiscount + $gstCost + $sgtCost + $serviceTaxCost;

        $order = $this->createOrder($netTotal);

        if ($order) {
            /** @var \App\Models\User $user */
            $newOrder = new Order();
            $highestId = Order::max('custom_id');
            $newCustomId = $highestId ? $highestId + 1 : 10000;
            if ($coupon) {
                $newOrder->coupon_id = $coupon->id;
                $newOrder->coupon_discount = $coupon_discount;
            }
            if ($request->points > 0) {
                $newOrder->discount = $point_discount;
                $newOrder->points = $request->points;
            }
            $newOrder->custom_id = $newCustomId;
            $newOrder->user_id = auth()->user()->id;
            $newOrder->order_amount =  floatval($totalProductPriceAfterDiscount);
            $newOrder->razorpay_order_id = $order->id;
            $newOrder->razorpay_payment_id = 'unpaid';
            $newOrder->payment_method =  'unpaid';
            $newOrder->net_amount = round($netTotal, 2);
            $newOrder->gst_amount = round($gstCost, 2);
            $newOrder->sgt_amount = round($sgtCost, 2);
            $newOrder->service_tax = round($serviceTaxCost, 2);

            $newOrder->status = 'unpaid';
            $newOrder->save();
            foreach ($cartItems as $cartItem) {
                $orderedItem = new OrderItem();
                $orderedItem->order_id = $newOrder->id;
                $orderedItem->item_id = $cartItem->item->id;
                $orderedItem->quantity = $cartItem->quantity;
                $orderedItem->status = 'unpaid';
                $orderedItemPrice = $cartItem->item->price * $cartItem->quantity;
                if ($coupon) {
                    if (empty($couponCategoryIds) && empty($couponItemIds)) {
                        $proportion = ($cartItem->item->price * $cartItem->quantity) / $totalProductPrice;
                        $earnings = $proportion * ($totalProductPriceAfterDiscount + $point_discount);
                        $orderedItemPrice = $earnings;
                    } elseif (!empty($couponCategoryIds) && empty($couponItemIds)) {
                        if (in_array($cartItem->item->category_id, $couponCategoryIds)) {
                            $proportion = ($cartItem->item->price * $cartItem->quantity) / $totalApplicableItemsPrice;
                            $earnings = $proportion * $totalApplicableItemsPriceAfterDiscount;
                            $orderedItemPrice = $earnings;
                        } else {
                            $orderedItemPrice = $cartItem->item->price * $cartItem->quantity;
                        }
                    } elseif (empty($couponCategoryIds) && !empty($couponItemIds)) {
                        if (in_array($cartItem->item->id, $couponItemIds)) {
                            $proportion = ($cartItem->item->price * $cartItem->quantity) / $totalApplicableItemsPrice;
                            $earnings = $proportion * $totalApplicableItemsPriceAfterDiscount;
                            $orderedItemPrice = $earnings;
                        } else {
                            $orderedItemPrice = $cartItem->item->price * $cartItem->quantity;
                        }
                    } else {
                        if (in_array($cartItem->item->category_id, $couponCategoryIds) && in_array($cartItem->item->id, $couponItemIds)) {
                            $proportion = ($cartItem->item->price * $cartItem->quantity) / $totalApplicableItemsPrice;
                            $earnings = $proportion * $totalApplicableItemsPriceAfterDiscount;
                            $orderedItemPrice = $earnings;
                        } else {
                            $orderedItemPrice = $cartItem->item->price * $cartItem->quantity;
                        }
                    }
                }
                if ($request->points > 0 && $coupon) {
                    if ($cartItem->item->points_status) {
                        $proportion = (($cartItem->item->price * $cartItem->quantity)  / ($totalProductPrice - $totalPointsNotApplicableItemCost));
                        $earnings = $proportion * abs($totalProductPriceAfterDiscount + $coupon_discount - $totalPointsNotApplicableItemCost);
                        $orderedItemPrice = abs($orderedItemPrice + $earnings - ($cartItem->item->price * $cartItem->quantity));
                    } else {
                        $totalApplicablePoints = 1;
                        $orderedItemPrice = ($orderedItemPrice * $totalApplicablePoints);
                    }
                } else if ($request->points > 0 && !$coupon) {
                    if ($cartItem->item->points_status) {
                        $proportion = ($cartItem->item->price * $cartItem->quantity) / ($totalProductPrice - $totalPointsNotApplicableItemCost);
                        $earnings = $proportion * abs($totalProductPriceAfterDiscount - $totalPointsNotApplicableItemCost);
                        $orderedItemPrice = $earnings;
                    } else {
                        $earnings = ($cartItem->item->price * $cartItem->quantity);
                        $orderedItemPrice = $earnings;
                    }
                }
                $orderedItem->price = round($orderedItemPrice / $cartItem->quantity, 2);
                $orderedItem->save();
            }
            foreach ($cartEvents as $cartEvent) {
                $orderedItem = new OrderItem();
                $orderedItem->order_id = $newOrder->id;
                $orderedItem->event_id = $cartEvent->event->id;
                $orderedItem->quantity = $cartEvent->quantity;
                $orderedItem->status = 'unpaid';
                $orderedItemPrice = $cartEvent->event->price * $cartEvent->quantity;
                if ($coupon) {
                    if (empty($couponCategoryIds) && empty($couponItemIds)) {
                        $proportion = ($cartEvent->event->price * $cartEvent->quantity) / $totalProductPrice;
                        $earnings = $proportion * ($totalProductPriceAfterDiscount + $point_discount);
                        $orderedItemPrice = $earnings;
                    }
                }
                if ($request->points > 0 && $coupon) {
                    $proportion = (($cartEvent->event->price * $cartEvent->quantity)  / ($totalProductPrice - $totalPointsNotApplicableItemCost));
                    $earnings = $proportion * abs($totalProductPriceAfterDiscount + $coupon_discount - $totalPointsNotApplicableItemCost);
                    $orderedItemPrice = abs($orderedItemPrice + $earnings - ($cartEvent->event->price * $cartEvent->quantity));
                } else if ($request->points > 0 && !$coupon) {
                    $proportion = ($cartEvent->event->price * $cartEvent->quantity) / ($totalProductPrice - $totalPointsNotApplicableItemCost);
                    $earnings = $proportion * abs($totalProductPriceAfterDiscount - $totalPointsNotApplicableItemCost);
                    $orderedItemPrice = $earnings;
                }
                $orderedItem->price = round($orderedItemPrice / $cartEvent->quantity, 2);
                $orderedItem->save();
            }

            foreach ($cartPlayAreas as $cartPlayArea) {
                $orderedItem = new OrderItem();
                $orderedItem->order_id = $newOrder->id;
                $orderedItem->play_area_id = $cartPlayArea->playArea->id;
                $orderedItem->quantity = $cartPlayArea->quantity;
                $orderedItem->status = 'unpaid';
                $start = isset($cartPlayArea->play_area_start_time) ? Carbon::parse($cartPlayArea->play_area_start_time) : null;
                $end = isset($cartPlayArea->play_area_end_time) ? Carbon::parse($cartPlayArea->play_area_end_time) : null;
                $durationInHours = 0;
                if ($start && $end) {
                    $durationInMinutes = $start->diffInMinutes($end);
                    $durationInHours = $durationInMinutes / 60;
                }
                $orderedItemPrice = $cartPlayArea->playArea->price * $durationInHours * $cartPlayArea->quantity;

                if ($coupon) {
                    if (empty($couponCategoryIds) && empty($couponItemIds)) {
                        $proportion = ($cartPlayArea->playArea->price * $durationInHours * $cartPlayArea->quantity) / $totalProductPrice;
                        $earnings = $proportion * ($totalProductPriceAfterDiscount + $point_discount);
                        $orderedItemPrice = $earnings;
                    }
                }
                if ($request->points > 0 && $coupon) {
                    $proportion = (($cartPlayArea->playArea->price * $durationInHours * $cartPlayArea->quantity)  / ($totalProductPrice - $totalPointsNotApplicableItemCost));
                    $earnings = $proportion * abs($totalProductPriceAfterDiscount + $coupon_discount - $totalPointsNotApplicableItemCost);
                    $orderedItemPrice = abs($orderedItemPrice + $earnings - ($cartPlayArea->playArea->price * $durationInHours * $cartPlayArea->quantity));
                } else if ($request->points > 0 && !$coupon) {
                    $proportion = ($cartPlayArea->playArea->price * $durationInHours * $cartPlayArea->quantity) / ($totalProductPrice - $totalPointsNotApplicableItemCost);
                    $earnings = $proportion * abs($totalProductPriceAfterDiscount - $totalPointsNotApplicableItemCost);
                    $orderedItemPrice = $earnings;
                }
                $orderedItem->price = round($orderedItemPrice / $cartPlayArea->quantity, 2);
                $orderedItem->play_area_date = $cartPlayArea->play_area_date;
                $orderedItem->play_area_start_time = $cartPlayArea->play_area_start_time;
                $orderedItem->play_area_end_time = $cartPlayArea->play_area_end_time;
                $orderedItem->save();
            }
        }


        return response()->json(['success' => true, 'total' => floatval($netTotal), 'order' => $order->id]);
    }

    private function createOrder($totalAmount)
    {
        $api_key = env('RAZORPAY_KEY');
        $api_secret = env('RAZORPAY_SECRET');

        $api = new Api($api_key, $api_secret);

        $amountInPaisa = round($totalAmount * 100);

        $order = $api->order->create([
            'amount' => $amountInPaisa, // Amount in paisa
            'currency' => 'INR',
            'receipt' => 'order_receipt_' . rand(),
            'payment_capture' => 1,
        ]);

        return $order;
    }

    public function handlePaymentCallback(Request $request)
    {

        $orderId = $request->input('razorpay_order_id');
        $paymentId = $request->input('razorpay_payment_id');
        $signature = $request->input('razorpay_signature');

        // Verify the Razorpay signature for security
        $api_key = env('RAZORPAY_KEY');
        $api_secret = env('RAZORPAY_SECRET');
        $api = new Api($api_key, $api_secret);
        $attributes = [
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ];

        // Verify the Razorpay signature
        $api->utility->verifyPaymentSignature($attributes);

        // Retrieve the order details based on the order ID
        $order = $api->order->fetch($orderId)->payments();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Create a new Order in your database
        $newOrder = Order::where('razorpay_order_id', $order->items[0]->order_id)->first();

        if (round($order->items[0]->amount, 2) === round($newOrder->net_amount * 100, 2)) {
            $newOrder->razorpay_payment_id = $paymentId;
            $newOrder->payment_method =  $order->items[0]->method;

            if ($order->items[0]->method == 'card') {
                $newOrder->payment_card_network = $order->items[0]->card->network;
                $newOrder->payment_card_last_4 = $order->items[0]->card->last4;
            } elseif ($order->items[0]->method == 'netbanking') {
                $newOrder->payment_card_network = $order->items[0]->bank;
            } else {
                $newOrder->payment_card_network = $order->items[0]->wallet;
            }

            $newOrder->status = 'paid';

            $totalPrice = $newOrder->order_amount;
            $orderItems = OrderItem::where('order_id', $newOrder->id)->get();
            $filteredOrderItems = $orderItems->reject(function ($orderItem) {
                return $orderItem->item_id === null;
            });

            if ($filteredOrderItems->count() < 1) {
                $newOrder->status = 'delivered';
            }
            $adminWallet = AdminWallet::first();
            $settings = Setting::first();
            $newOrder->update();

            foreach ($orderItems as $orderItem) {
                if ($orderItem->play_area_id) {
                    $orderItem->status = 'delivered';
                    $proportion = ($orderItem->price *  $orderItem->quantity) / $totalPrice;
                } elseif ($orderItem->item_id) {
                    $orderItem->status = 'pending';
                    $proportion = ($orderItem->price *  $orderItem->quantity) / $totalPrice;
                } elseif ($orderItem->event_id) {
                    $orderItem->status = 'delivered';
                    $proportion = ($orderItem->price *  $orderItem->quantity) / $totalPrice;
                }

                $orderItem->save();
                $earnings = $proportion * $newOrder->order_amount;

                if ($orderItem->item_id) {
                    $vendorId = $orderItem->item->vendor_id;
                    $vendorBank = VendorBank::where('vendor_id', $vendorId)->first();

                    $adminEarnings = $earnings * ($orderItem->item->vendor->commission / 100);

                    $vendorEarnings = $earnings - $adminEarnings;

                    // Update the balances
                    $vendorBank->balance += $vendorEarnings;
                    $vendorBank->total_earning += $vendorEarnings;
                    $adminWallet->balance += $adminEarnings;
                    $adminWallet->total_earning += $adminEarnings;

                    AdminWalletLog::create([
                        'action' => 'deposit',
                        'amount' => $adminEarnings
                    ]);
                    VendorBankLog::create([
                        'action' => 'deposit',
                        'amount' => $vendorEarnings,
                        'vendor_id' => $vendorId
                    ]);

                    $vendorBank->save();
                    $adminWallet->save();

                    if (!$settings->gst_admin) {
                        $gstCost = round($earnings * ($settings->gst / 100), 2);
                        $vendorBank->balance += $gstCost;
                        $vendorBank->total_earning += $gstCost;
                        VendorBankLog::create([
                            'action' => 'deposit',
                            'amount' => $gstCost,
                            'vendor_id' => $vendorId
                        ]);
                        $vendorBank->save();
                    }

                    if (!$settings->sgt_admin) {
                        $sgtCost = round($earnings * ($settings->sgt / 100), 2);
                        $vendorBank->balance += $sgtCost;
                        $vendorBank->total_earning += $sgtCost;
                        VendorBankLog::create([
                            'action' => 'deposit',
                            'amount' => $sgtCost,
                            'vendor_id' => $vendorId
                        ]);
                        $vendorBank->save();
                    }
                } else {
                    $adminWallet->balance += $earnings;
                    $adminWallet->total_earning += $earnings;

                    AdminWalletLog::create([
                        'action' => 'deposit',
                        'amount' => $earnings
                    ]);

                    $adminWallet->save();

                    if (!$settings->gst_admin) {
                        $gstCost = round($earnings * ($settings->gst / 100), 2);
                        $adminWallet->balance += $gstCost;
                        $adminWallet->total_earning += $gstCost;
                        AdminWalletLog::create([
                            'action' => 'deposit',
                            'amount' => $gstCost
                        ]);
                        $adminWallet->save();
                    }

                    if (!$settings->sgt_admin) {
                        $sgtCost = round($earnings * ($settings->sgt / 100), 2);
                        $adminWallet->balance += $sgtCost;
                        $adminWallet->total_earning += $sgtCost;
                        AdminWalletLog::create([
                            'action' => 'deposit',
                            'amount' => $sgtCost
                        ]);
                        $adminWallet->save();
                    }
                }
            }

            if ($settings->gst_admin) {
                $adminWallet->balance += $newOrder->gst_amount;
                $adminWallet->total_earning += $newOrder->gst_amount;
                AdminWalletLog::create([
                    'action' => 'deposit',
                    'amount' => $newOrder->gst_amount
                ]);
                $adminWallet->save();
            }

            if ($settings->sgt_admin) {
                $adminWallet->balance += $newOrder->sgt_amount;
                $adminWallet->total_earning += $newOrder->sgt_amount;
                AdminWalletLog::create([
                    'action' => 'deposit',
                    'amount' => $newOrder->sgt_amount
                ]);
                $adminWallet->save();
            }

            if ($newOrder->service_tax > 0) {
                $adminWallet->balance += $newOrder->service_tax;
                $adminWallet->total_earning += $newOrder->service_tax;
                AdminWalletLog::create([
                    'action' => 'deposit',
                    'amount' => $newOrder->service_tax
                ]);
                $adminWallet->save();
            }

            $point_data = Point::first();
            $orderPointStatus = $point_data->order_points['status'];

            if ($request->points > 0) {
                $user->point->points = $orderPointStatus == 'active' ? $user->point->points - $request->points +  $point_data->order_points['points'] : $user->point->points - $request->points;
            } else {
                if ($orderPointStatus == 'active') {
                    $user->point->points = $user->point->points + $point_data->order_points['points'];
                }
            }

            $orderFoodItemsCount = OrderItem::where('order_id', $newOrder->id)->whereNot('item_id', null)->count();

            if ($orderFoodItemsCount > 0 && $point_data->order_points['points'] > 0 && $orderPointStatus == 'active') {
                $user->point->points = $user->point->points + $point_data->order_points['points'];
                CustomerPointLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Order',
                    'points' => $point_data->order_points['points'] ?? 0,
                    'order_id' => $newOrder->id,
                    'details' => 'Points added of Order Placed'
                ]);
                Notification::create([
                    'user_id' => Auth::id(),
                    'message' => $point_data->order_points['alert_message'],
                ]);
                try {
                    if (auth()->user()->email) {
                        Mail::to(auth()->user()->email)->send(new PointsCreditEmail(auth()->user()->id, $point_data->order_points['points'], $point_data->order_points['alert_message']));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }

            $orderPlayAreaCount = OrderItem::where('order_id', $newOrder->id)->whereNot('play_area_id', null)->count();
            $playAreaPointStatus = $point_data->play_area_points['status'];

            if ($orderPlayAreaCount > 0 && $point_data->play_area_points['points'] > 0 && $playAreaPointStatus == 'active') {
                $user->point->points = $user->point->points + $point_data->play_area_points['points'];
                CustomerPointLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Booking',
                    'points' => $point_data->play_area_points['points'] ?? 0,
                    'order_id' => $newOrder->id,
                    'details' => 'Points added for booking play area'
                ]);
                Notification::create([
                    'user_id' => Auth::id(),
                    'message' => $point_data->play_area_points['alert_message'],
                ]);
                try {
                    if (auth()->user()->email) {
                        Mail::to(auth()->user()->email)->send(new PointsCreditEmail(auth()->user()->id, $point_data->play_area_points['points'], $point_data->play_area_points['alert_message']));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }

            $orderEventCount = OrderItem::where('order_id', $newOrder->id)->whereNot('event_id', null)->count();
            $eventPointStatus = $point_data->event_points['status'];

            if ($orderEventCount > 0 && $point_data->event_points['points'] > 0 && $eventPointStatus == 'active') {
                $user->point->points = $user->point->points + $point_data->event_points['points'];
                CustomerPointLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Booking',
                    'points' => $point_data->event_points['points'] ?? 0,
                    'order_id' => $newOrder->id,
                    'details' => 'Points added for booking events'
                ]);
                Notification::create([
                    'user_id' => Auth::id(),
                    'message' => $point_data->event_points['alert_message'],
                ]);
                try {
                    if (auth()->user()->email) {
                        Mail::to(auth()->user()->email)->send(new PointsCreditEmail(auth()->user()->id, $point_data->event_points['points'], $point_data->event_points['alert_message']));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }

            if ($request->points > 0) {
                $points = $request->points;
                CustomerPointLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Redeem',
                    'points' => $points,
                    'order_id' => $newOrder->id,
                    'details' => 'Points redeem on Order'
                ]);
            }
            $user->point->save();
            $user->cartItems()->delete();

            try {
                if ($user->email) {
                    Mail::to($user->email)->send(new OrderPlacedEmail($user->id, $newOrder, $order->items[0]));
                }
            } catch (\Exception $e) {
                // Log the exception or perform any other error handling
                Log::error('Failed to send email: ' . $e->getMessage());
            }

            $uniqueVendorIds = [];
            if ($orderItem->item_id) {
                foreach ($orderItems as $orderItem) {
                    $vendorId = $orderItem->item->vendor->user->id;
                    if (!in_array($vendorId, $uniqueVendorIds)) {
                        $uniqueVendorIds[] = $vendorId;
                    }
                }
                $url = '/vendor/orders/' . $newOrder->id;
                $message = 'New order from your store!';
                foreach ($uniqueVendorIds as $vendorId) {
                    $user =  User::where('id', $vendorId)->first();
                    if ($user && $user->onesignal_subs_id) {
                        $userId = $user->onesignal_subs_id;
                        $params = [];
                        $params['include_player_ids'] = [$userId];
                        $params['url'] = $url;
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignalFacade::sendNotificationCustom(
                            $params
                        );
                    }
                }
            }

            if (auth()->user()->onesignal_subs_id) {
                $userId = auth()->user()->onesignal_subs_id;
                $params = [];
                $params['include_player_ids'] = [$userId];
                $params['url'] = '/orders/' . $newOrder->id;
                $message = "You order is placed!";
                $contents = [
                    "en" => $message,
                ];
                $params['contents'] = $contents;
                OneSignalFacade::sendNotificationCustom(
                    $params
                );
            }
            $request->session()->forget('cart.items');
            $request->session()->forget('cart.events');
            $request->session()->forget('cart.playAreas');
            broadcast(new DashboardStatesUpdateEvent('orderPlaced', []));
            foreach ($uniqueVendorIds as $vendorUserId) {
                broadcast(new DashboardStatesUpdateEvent('orderPlaced', [], $vendorUserId));
            }
            return response()->json(['success' => true]);
        }
    }

    public function handlePaymentFailed(Request $request)
    {
        $orderId = $request->input('order_id');
        // Create a new Order in your database
        $newOrder = Order::where('razorpay_order_id', $orderId)->first();

        if ($newOrder) {
            $newOrder->status = 'canceled';
            $newOrder->update();

            $orderItems = OrderItem::where('order_id', $newOrder->id)->get();

            foreach ($orderItems as $orderItem) {
                $ot = OrderItem::where('id', $orderItem->id)->first();
                $ot->status = 'canceled';
                $ot->update();
            }
        }
        return response()->json(['success' => true]);
    }
}
