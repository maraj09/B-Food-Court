<?php

namespace App\Http\Controllers\Admin;

use App\Events\DashboardStatesUpdateEvent;
use App\Events\OrderStatusChangeEvent;
use App\Http\Controllers\Controller;
use App\Mail\OrderInvoiceEmail;
use App\Models\AdminWallet;
use App\Models\AdminWalletLog;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Point;
use App\Models\Setting;
use App\Models\User;
use App\Models\VendorBank;
use App\Models\VendorBankLog;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Razorpay\Api\Api;
use stdClass;
use App\Http\Controllers\Customer\AppController as CustomerAppController;
use App\Models\Event;
use App\Models\ItemCategory;
use App\Models\PlayArea;
use Carbon\Carbon;

class OrdersController extends Controller
{
    protected $getCustomerAppControllerData;

    public function __construct(CustomerAppController $customerAppController)
    {
        $this->getCustomerAppControllerData = $customerAppController;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('status', '!=', 'unpaid')->where('payment_method', '!=', 'unpaid')->orderBy('created_at', 'desc')->get();
        return view('pages.orders.admin.orders', compact(['orders']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::where('status', 1)->where('approve', 1)->get();
        $playAreas = PlayArea::where('status', 1)->get();
        $events = Event::where('status', 1)
            ->where('start_date', '>=', Carbon::now())
            ->get();
        $customerUsers = User::role('customer')->get();
        $highestId = Order::max('custom_id');
        $newCustomId = $highestId ? $highestId + 1 : 10000;
        $itemCategories = ItemCategory::all();
        $coupons = Coupon::where('status', 1)->where('approved', 1)->get();
        $coupons = $coupons->filter(function ($coupon) {
            $result = $this->getCustomerAppControllerData->validateCoupon($coupon, null, true);
            return $result['coupon_success'] ?? true;
        })->values();
        $settings = Setting::first();
        return view('pages.orders.admin.cashier-place-order', compact(['items', 'customerUsers', 'newCustomId', 'itemCategories', 'coupons', 'settings', 'playAreas', 'events']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required',
            'status' => 'required',
            'items' => 'not_in:[]',
            'events' => 'not_in:[]',
            'playAreas' => 'not_in:[]',
            'coupon_code' => [
                'nullable',
                Rule::exists('coupons', 'code')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
        ]);

        if (!$request->filled('items') && !$request->filled('events') && !$request->filled('playAreas')) {
            return response()->json(['success' => false, 'message' => 'Please select at least 1 item.']);
        }

        if ($request->ajax == 'ajax') {
            $selectedItems = $request->input('items', []);
            $selectedEvents = $request->input('events', []);
            $selectedPlayAreas = $request->input('playAreas', []);
        } else {
            $selectedItems = json_decode($request->input('selected_items'), true);
        }
        $user = User::where('id', $request->user_id)->first();

        $newOrder = new Order();
        $highestId = Order::max('custom_id');
        $newCustomId = $highestId ? $highestId + 1 : 10000;
        $newOrder->custom_id = $newCustomId;
        $newOrder->user_id = $request->user_id;

        $totalProductsPrice = 0;
        $totalItemsPrice = 0;
        $totalEventsPrice = 0;
        $totalPlayAreasPrice = 0;

        foreach ($selectedItems as $item) {
            $itemID = $item['id'];
            $quantity = $item['quantity'];
            $totalItemsPrice += Item::where('id', $itemID)->first()->price * $quantity;
        }

        foreach ($selectedEvents as $event) {
            $eventValidation = $this->getCustomerAppControllerData->validateEvent($event['id'], $event['quantity']);
            $eventValid = $eventValidation['success'] ?? true;
            if (!$eventValid) {
                return response()->json($eventValidation);
            }
        }
        foreach ($selectedEvents as $event) {
            $itemID = $event['id'];
            $quantity = $event['quantity'];
            $totalEventsPrice += Event::where('id', $itemID)->first()->price * $quantity;
        }

        try {
            foreach ($selectedPlayAreas as $playArea) {
                $date = $playArea['date'];
                $start = isset($playArea['start_time']) ? Carbon::parse($playArea['start_time']) : null;
                $end = isset($playArea['end_time']) ? Carbon::parse($playArea['end_time']) : null;

                if (!$start || !$end || !$date) {
                    throw new \Exception('Invalid Play Area: Missing start time, end time, or date.');
                }

                $durationInMinutes = $start->diffInMinutes($end);
                $durationInHours = $durationInMinutes / 60;
                $totalPlayAreasPrice += $playArea['quantity'] * $durationInHours * $playArea['price'];
            }
        } catch (\Exception $e) {
            // Log the specific error for debugging
            Log::error('Error calculating total play areas price: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Invalid Play Area: Missing start time, end time, or date.']);
        }

        foreach ($selectedPlayAreas as $playArea) {
            $playAreaValidation = $this->getCustomerAppControllerData->validatePlayArea($playArea['id'], $playArea['date'], $playArea['start_time'], $playArea['end_time'], $playArea['quantity']);
            $playAreaValid = $playAreaValidation['success'] ?? true;
            if (!$playAreaValid) {
                return response()->json($playAreaValidation);
            }
        }

        $totalProductsPrice = round($totalItemsPrice + $totalEventsPrice + $totalPlayAreasPrice);


        $totalProductPriceAfterDiscount = $totalProductsPrice;
        $coupon_discount = 0;
        $couponCategoryIds = [];
        $couponItemIds = [];
        $totalApplicableItemsPrice = 0;
        $totalApplicableItemsPriceAfterDiscount = 0;
        $coupon = null;
        if ($request->coupon_code) {
            $coupon = Coupon::where('code', $request->coupon_code)->where('status', 1)->first();
            if ($coupon) {
                $couponValidation = $this->getCustomerAppControllerData->validateCoupon($coupon, $totalProductsPrice, true);
                $couponCategoryIds = $coupon->itemCategories->pluck('id')->toArray();
                $couponItemIds = $coupon->items->pluck('id')->toArray();
                $isCouponValid = $couponValidation['coupon_success'] ?? true;
                if (!$isCouponValid) {
                    return response()->json(['success' => false, 'message' => 'Oops! Invalid Coupon.']);
                } else {
                    if (!empty($couponCategoryIds) && !empty($couponItemIds)) {
                        foreach ($selectedItems as $item) {
                            $itemId = $item['id'];
                            $quantity = $item['quantity'];
                            $item = Item::where('id', $itemId)->first();
                            $cartItemCategoryId = $item->category_id;
                            $cartItemId = $item->id;
                            if (in_array($cartItemCategoryId, $couponCategoryIds) && in_array($cartItemId, $couponItemIds)) {
                                $totalApplicableItemsPrice += $item->price * $quantity;
                            }
                        }
                    } elseif (!empty($couponCategoryIds) && empty($couponItemIds)) {
                        foreach ($selectedItems as $item) {
                            $itemId = $item['id'];
                            $quantity = $item['quantity'];
                            $item = Item::where('id', $itemId)->first();
                            $cartItemCategoryId = $item->category_id;
                            if (in_array($cartItemCategoryId, $couponCategoryIds)) {
                                $totalApplicableItemsPrice += $item->price * $quantity;
                            }
                        }
                    } elseif (empty($couponCategoryIds) && !empty($couponItemIds)) {
                        foreach ($selectedItems as $item) {
                            $itemId = $item['id'];
                            $quantity = $item['quantity'];
                            $item = Item::where('id', $itemId)->first();
                            $cartItemId = $item->id;
                            if (in_array($cartItemId, $couponItemIds)) {
                                $totalApplicableItemsPrice += $item->price * $quantity;
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

        $newOrder->order_amount =  floatval($totalProductPriceAfterDiscount);
        $newOrder->razorpay_order_id = 'admin';
        $newOrder->razorpay_payment_id = 'admin';
        $newOrder->payment_method =  $request->payment_method;
        $newOrder->status =  $request->status;
        if ($coupon) {
            $newOrder->coupon_id = $coupon->id;
            $newOrder->coupon_discount = $coupon_discount;
        }
        $newOrder->net_amount = round($netTotal, 2);
        $newOrder->gst_amount = round($gstCost, 2);
        $newOrder->sgt_amount = round($sgtCost, 2);
        $newOrder->service_tax = round($serviceTaxCost, 2);
        $newOrder->save();
        $adminWallet = AdminWallet::first();

        foreach ($selectedItems as $item) {
            $itemID = $item['id'];
            $quantity = $item['quantity'];
            $item = Item::where('id', $itemID)->first();
            $orderedItem = new OrderItem();
            $orderedItem->order_id = $newOrder->id;

            $orderedItem->item_id = $itemID;
            $orderedItem->quantity = $quantity;
            $orderedItem->status = 'pending';
            $orderedItemPrice = 0;
            $earnings = $item->price * $quantity;
            if ($coupon) {
                if (empty($couponCategoryIds) && empty($couponItemIds)) {
                    $proportion = ($item->price * $quantity) / $totalProductsPrice;
                    $earnings = $proportion * $totalProductPriceAfterDiscount;
                    $orderedItemPrice = round($earnings / $quantity, 1);
                } elseif (!empty($couponCategoryIds) && empty($couponItemIds)) {
                    if (in_array($item->category_id, $couponCategoryIds)) {
                        $proportion = ($item->price * $quantity) / $totalApplicableItemsPrice;
                        $earnings = $proportion * $totalApplicableItemsPriceAfterDiscount;
                        $orderedItemPrice = round($earnings / $quantity, 1);
                    } else {
                        $orderedItemPrice = $item->price;
                    }
                } elseif (empty($couponCategoryIds) && !empty($couponItemIds)) {
                    if (in_array($item->id, $couponItemIds)) {
                        $proportion = ($item->price * $quantity) / $totalApplicableItemsPrice;
                        $earnings = $proportion * $totalApplicableItemsPriceAfterDiscount;
                        $orderedItemPrice = round($earnings / $quantity, 1);
                    } else {
                        $orderedItemPrice = $item->price;
                    }
                } else {
                    if (in_array($item->category_id, $couponCategoryIds) && in_array($item->id, $couponItemIds)) {
                        $proportion = ($item->price * $quantity) / $totalApplicableItemsPrice;
                        $earnings = $proportion * $totalApplicableItemsPriceAfterDiscount;
                        $orderedItemPrice = round($earnings / $quantity, 1);
                    } else {
                        $orderedItemPrice = $item->price;
                    }
                }
                $orderedItem->price = $orderedItemPrice;
            } else {
                $proportion = ($item->price * $quantity) / $totalProductsPrice;
                $earnings = $proportion * $totalProductPriceAfterDiscount;
                $orderedItemPrice = round($earnings / $quantity, 1);
                $orderedItem->price = $orderedItemPrice;
            }
            $orderedItem->save();

            $vendorId = $item->vendor_id;
            $vendorBank = VendorBank::where('vendor_id', $vendorId)->first();

            // Calculate the vendor's earnings (based on vendor commission)
            $adminEarnings = $earnings * ($orderedItem->item->vendor->commission / 100);

            // Calculate the admin's earnings (remaining earnings after vendor commission)
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
        }

        foreach ($selectedEvents as $cartEvent) {
            $event = Event::where('id', $cartEvent['id'])->first();
            $orderedItem = new OrderItem();
            $orderedItem->order_id = $newOrder->id;
            $orderedItem->event_id = $cartEvent['id'];
            $orderedItem->quantity = $cartEvent['quantity'];
            $orderedItem->status = 'delivered';
            if (empty($couponCategoryIds) && empty($couponItemIds)) {
                $proportion = ($event->price * $cartEvent['quantity']) / $totalProductsPrice;
                $earnings = $proportion * ($totalProductPriceAfterDiscount);
                $orderedItem->price = round($earnings / $cartEvent['quantity'], 1);
            } else {
                $orderedItem->price = $event->price;
            }
            $orderedItem->save();
            $adminWallet->balance += $orderedItem->price;
            $adminWallet->total_earning += $orderedItem->price;

            AdminWalletLog::create([
                'action' => 'deposit',
                'amount' => $orderedItem->price * $orderedItem->quantity
            ]);

            $adminWallet->save();

            if (!$settings->gst_admin) {
                $gstCost = round($orderedItem->price * $orderedItem->quantity * ($settings->gst / 100), 2);
                $adminWallet->balance += $gstCost;
                $adminWallet->total_earning += $gstCost;
                AdminWalletLog::create([
                    'action' => 'deposit',
                    'amount' => $gstCost
                ]);
                $adminWallet->save();
            }

            if (!$settings->sgt_admin) {
                $sgtCost = round($orderedItem->price * $orderedItem->quantity * ($settings->sgt / 100), 2);
                $adminWallet->balance += $sgtCost;
                $adminWallet->total_earning += $sgtCost;
                AdminWalletLog::create([
                    'action' => 'deposit',
                    'amount' => $sgtCost
                ]);
                $adminWallet->save();
            }
        }

        foreach ($selectedPlayAreas as $playArea) {
            $orderedItem = new OrderItem();
            $orderedItem->order_id = $newOrder->id;
            $orderedItem->play_area_id = $playArea['id'];
            $orderedItem->quantity = $playArea['quantity'];
            $orderedItem->status = 'delivered';

            if (empty($couponCategoryIds) && empty($couponItemIds)) {
                $proportion = ($playArea['price'] * $playArea['quantity'] * $playArea['duration']) / $totalProductsPrice;
                $earnings = $proportion * ($totalProductPriceAfterDiscount);
                $orderedItem->price = round($earnings / $playArea['quantity'], 1);
            } else {
                $orderedItem->price = $playArea['price'] * $playArea['duration'];
            }
            $orderedItem->play_area_date = $playArea['date'];
            $orderedItem->play_area_start_time = $playArea['start_time'];
            $orderedItem->play_area_end_time = $playArea['end_time'];
            $orderedItem->save();

            $adminWallet->balance += $orderedItem->price;
            $adminWallet->total_earning += $orderedItem->price;

            AdminWalletLog::create([
                'action' => 'deposit',
                'amount' => $orderedItem->price * $orderedItem->quantity
            ]);

            $adminWallet->save();

            if (!$settings->gst_admin) {
                $gstCost = round($orderedItem->price * $orderedItem->quantity * ($settings->gst / 100), 2);
                $adminWallet->balance += $gstCost;
                $adminWallet->total_earning += $gstCost;
                AdminWalletLog::create([
                    'action' => 'deposit',
                    'amount' => $gstCost
                ]);
                $adminWallet->save();
            }

            if (!$settings->sgt_admin) {
                $sgtCost = round($orderedItem->price * $orderedItem->quantity * ($settings->sgt / 100), 2);
                $adminWallet->balance += $sgtCost;
                $adminWallet->total_earning += $sgtCost;
                AdminWalletLog::create([
                    'action' => 'deposit',
                    'amount' => $sgtCost
                ]);
                $adminWallet->save();
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

        $uniqueVendorIds = [];
        foreach ($selectedItems as $orderItem) {
            $orderItem = Item::where('id', $orderItem['id'])->first();
            $vendorId = $orderItem->vendor->user->id;
            if (!in_array($vendorId, $uniqueVendorIds)) {
                $uniqueVendorIds[] = $vendorId;
            }
        }
        $url = '/vendor/orders/' . $newOrder->id;
        $message = 'New order from your store!';
        foreach ($uniqueVendorIds as $vendorId) {
            $user = User::where('id', $vendorId)->first();
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
        broadcast(new DashboardStatesUpdateEvent('orderPlaced', []));
        foreach ($uniqueVendorIds as $vendorUserId) {
            broadcast(new DashboardStatesUpdateEvent('orderPlaced', [], $vendorUserId));
        }
        if ($request->ajax == 'ajax') {
            return response()->json(['success' => true]);
        }
        return redirect('/dashboard/orders')->with('success', 'Order submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('pages.orders.admin.orderView', compact(['order']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id); // Assuming $id is the item ID you want to delete

        $order->orderItems()->delete();

        // Delete the item
        $order->delete();

        // Redirect or respond as needed
        return redirect('/dashboard/orders')->with('success', 'Order deleted successfully');
    }

    public function updateItemStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $item = OrderItem::findOrFail($id);
        $order = Order::where('id', $item->order_id)->first();

        if (!auth()->user()->can('orders-management')) {
            $disallowedStatuses = ['accepted', 'completed', 'delivered'];

            if ($order->razorpay_order_id == 'admin') {
                if ($status === 'pending' || $status === 'rejected') {
                    if (in_array($item->status, $disallowedStatuses)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'You can\'t update the status.',
                            'status' => $item->status,
                            'orderStatus' => $order->status,
                            'orderId' => $order->id,
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'You can\'t update the status.',
                        'status' => $item->status,
                        'orderStatus' => $order->status,
                        'orderId' => $order->id,
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'You can\'t update the status.',
                    'status' => $item->status,
                    'orderStatus' => $order->status,
                    'orderId' => $order->id,
                ]);
            }
        }

        if ($item->refunded == true) {
            return response()->json([
                'success' => false,
                'message' => 'Item is already refunded.',
                'status' => $item->status,
                'orderStatus' => $order->status,
                'orderId' => $order->id,
            ]);
        }

        $item->status = $status;
        $item->save();

        $orderStatus = 'pending';

        // Assume all items are delivered until proven otherwise
        $allItemsDelivered = true;
        $allItemsRejected = true;

        // Iterate through all the order items
        foreach ($order->orderItems()->whereNot('item_id', null)->get() as $orderItem) {
            // Check the status of each order item
            if ($orderItem->status !== 'delivered') {
                // If any order item is not delivered, set the delivered flag to false
                $allItemsDelivered = false;
            }

            if ($orderItem->status !== 'rejected') {
                // If any order item is not rejected, set the rejected flag to false
                $allItemsRejected = false;
            }
        }

        // Determine the order status based on all items' statuses
        if ($allItemsDelivered) {
            $orderStatus = 'delivered';
        } elseif ($allItemsRejected) {
            $orderStatus = 'rejected';
        }
        // Update order status
        $order->status = $orderStatus;
        $order->save();

        broadcast(new OrderStatusChangeEvent($order, $item));

        $message = "Your Order item " . $item->item->item_name . "'s status is changed to " . $item->status .  "!";
        $url = "/orders/" . $item->order_id;
        if ($item->order->user->onesignal_subs_id) {
            $userId = $item->order->user->onesignal_subs_id;
            $params = [];
            $params['include_player_ids'] = [$userId];
            $params['url'] = $url;
            $params['chrome_web_image'] = asset($item->item->item_image);
            $contents = [
                "en" => $message,
            ];
            $params['contents'] = $contents;
            OneSignalFacade::sendNotificationCustom(
                $params
            );
        }

        $deliveredItemsCount = 0;
        $rejectedItemsCount = 0;

        foreach ($order->orderItems()->whereNot('item_id', null)->get() as $orderItem) {
            if ($orderItem->status == 'delivered') {
                $deliveredItemsCount++;
            } elseif ($orderItem->status == 'rejected') {
                $rejectedItemsCount++;
            }
        }

        $totalItemsCount = count($order->orderItems()->whereNot('item_id', null)->get());

        // Check conditions to decide whether to send the mail
        if ($deliveredItemsCount == $totalItemsCount) {
            if ($order->user->email) {
                if ($order->razorpay_order_id != 'admin') {
                    $payment = new stdClass();
                    $payment->method = $order->payment_method;
                    $payment->last4 = $order->payment_card_last_4;
                    $payment->network = $order->payment_card_network;
                    $payment->bank = $order->payment_card_network;
                    $payment->wallet = $order->payment_card_network;
                    try {
                        Mail::to($order->user->email)->send(new OrderInvoiceEmail($order->user->id, $order, $payment));
                    } catch (\Exception $e) {
                        // Log the exception or perform any other error handling
                        Log::error('Failed to send email: ' . $e->getMessage());
                    }
                } else {
                    $payment = new stdClass();
                    $payment->method = 'admin';
                    $payment->wallet = $order->payment_method;
                    try {
                        Mail::to($order->user->email)->send(new OrderInvoiceEmail($order->user->id, $order, $payment));
                    } catch (\Exception $e) {
                        // Log the exception or perform any other error handling
                        Log::error('Failed to send email: ' . $e->getMessage());
                    }
                }
            }
        } elseif ($deliveredItemsCount > 0 && $rejectedItemsCount > 0 && ($deliveredItemsCount + $rejectedItemsCount == $totalItemsCount)) {
            if ($order->user->email) {
                if ($order->razorpay_order_id != 'admin') {
                    $payment = new stdClass();
                    $payment->method = $order->payment_method;
                    $payment->last4 = $order->payment_card_last_4;
                    $payment->network = $order->payment_card_network;
                    $payment->bank = $order->payment_card_network;
                    $payment->wallet = $order->payment_card_network;
                    try {
                        Mail::to($order->user->email)->send(new OrderInvoiceEmail($order->user->id, $order, $payment));
                    } catch (\Exception $e) {
                        // Log the exception or perform any other error handling
                        Log::error('Failed to send email: ' . $e->getMessage());
                    }
                } else {
                    $payment = new stdClass();
                    $payment->method = 'admin';
                    $payment->wallet = $order->payment_method;
                    try {
                        Mail::to($order->user->email)->send(new OrderInvoiceEmail($order->user->id, $order, $payment));
                    } catch (\Exception $e) {
                        // Log the exception or perform any other error handling
                        Log::error('Failed to send email: ' . $e->getMessage());
                    }
                }
            }
        }

        $response = [
            'success' => true,
            'status' => $item->status,
            'orderStatus' => $orderStatus,
            'orderId' => $order->id,
            'message' => 'Status updated successfully.',

        ];
        return response()->json($response);
    }

    public function search(Request $request)
    {
        // Retrieve search query from the request
        $searchQuery = $request->input('search');

        // Perform the search query to retrieve orders
        $orders = Order::whereHas('user', function ($query) use ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%');
        })->orWhere('id', 'like', '%' . $searchQuery . '%')->orderByDesc('created_at')
            ->get();


        // return response()->json($orders);

        $htmlContent = view('pages.orders.admin.partials.orders_table', ['orders' => $orders])->render();
        return response()->json(['htmlContent' => $htmlContent]);
    }

    public function refund(Request $request)
    {
        $item = OrderItem::findOrFail($request->itemId);
        $order = Order::where('id', $item->order_id)->first();

        // Check if the item status is changing to 'rejected'
        if ($item->status === 'rejected' && !$item->refunded) {
            $api_key = env('RAZORPAY_KEY');
            $api_secret = env('RAZORPAY_SECRET');

            $paymentId = $order->razorpay_payment_id;

            if ($paymentId !== null && $paymentId !== 'admin') {
                $api = new Api($api_key, $api_secret);
                $payment = $api->payment->fetch($paymentId);

                $refundAmount = $item->price * 100; // Convert amount to paisa for Razorpay

                // Initiate refund for the rejected item
                $refund = $api->refund->create([
                    'payment_id' => $paymentId,
                    'amount' => $refundAmount,
                    'speed' => 'optimum',
                    'reason' => 'Item got rejected!',
                ]);

                if ($refund->status === 'processed') {
                    // Calculate the proportion of earnings for the rejected item
                    $earnings = $item->price * $item->quantity;

                    // Calculate vendor's earnings and admin's earnings
                    $vendorCommission = $item->item->vendor->commission;
                    $vendorEarnings = $earnings * (1 - $vendorCommission / 100);
                    $adminEarnings = $earnings - $vendorEarnings;

                    // Deduct earnings from vendor's balance
                    $vendorBank = VendorBank::where('vendor_id', $item->item->vendor_id)->first();
                    $vendorBank->balance -= $vendorEarnings;
                    $vendorBank->total_earning -= $vendorEarnings;
                    $vendorBank->save();

                    // Deduct earnings from admin's balance
                    $adminWallet = AdminWallet::first();
                    $adminWallet->balance -= $adminEarnings;
                    $adminWallet->total_earning -= $adminEarnings;
                    $adminWallet->save();

                    AdminWalletLog::create([
                        'action' => 'withdraw',
                        'amount' => $adminEarnings
                    ]);
                    VendorBankLog::create([
                        'action' => 'withdraw',
                        'amount' => $vendorEarnings,
                        'vendor_id' => $item->item->vendor_id
                    ]);
                }
            } else {
                $earnings = $item->price * $item->quantity;

                // Calculate vendor's earnings and admin's earnings
                $vendorCommission = $item->item->vendor->commission;
                $vendorEarnings = $earnings * (1 - $vendorCommission / 100);
                $adminEarnings = $earnings - $vendorEarnings;

                // Deduct earnings from vendor's balance
                $vendorBank = VendorBank::where('vendor_id', $item->item->vendor_id)->first();
                $vendorBank->balance -= $vendorEarnings;
                $vendorBank->total_earning -= $vendorEarnings;
                $vendorBank->save();

                // Deduct earnings from admin's balance
                $adminWallet = AdminWallet::first();
                $adminWallet->balance -= $adminEarnings;
                $adminWallet->total_earning -= $adminEarnings;
                $adminWallet->save();

                AdminWalletLog::create([
                    'action' => 'withdraw',
                    'amount' => $adminEarnings
                ]);
                VendorBankLog::create([
                    'action' => 'withdraw',
                    'amount' => $vendorEarnings,
                    'vendor_id' => $item->item->vendor_id
                ]);
            }
        } else {
            return response()->json(['message' => 'You have to rejected the item before refund.'], 500);
        }
        $item->refunded = 1;
        $item->save();
        return response()->json(['message' => 'Item refunded successfully.'], 200);
    }

    public function itemsSearch(Request $request)
    {
        // Retrieve search query from the request
        $searchQuery = $request->input('search');

        // Perform the search query to retrieve orders
        $items = Item::where('status', 1)->where('approve', 1)->where('item_name', 'like', "%$searchQuery%")->get();


        // return response()->json($orders);

        $htmlContent = view('pages.orders.admin.partials.orders_table', ['items' => $items])->render();
        return response()->json(['htmlContent' => $htmlContent]);
    }

    public function getOrderDrawer(Request $request)
    {
        $orderId = $request->input('id');

        // Retrieve customer data based on customer ID
        $order = Order::find($orderId);

        if (!$orderId) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Render the drawer card view with customer data
        $drawerContent = view('pages.orders.admin.partials.orderDrawerCard', ['order' => $order])->render();

        // Return the rendered drawer card HTML as part of the AJAX response
        return response()->json(['drawerContent' => $drawerContent]);
    }

    public function getOrderDrawerForVendor(Request $request)
    {
        $vendorId = $request->input('vendorId');
        $orderId = $request->input('orderId');

        // Retrieve customer data based on customer ID
        $order = Order::find($orderId);

        $orderItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->where('order_id', $order->id)->where('status', '!=', 'unpaid')->get();

        if (!$orderId) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Render the drawer card view with customer data
        $drawerContent = view('pages.vendors.admin.partials.orderDrawerCard', ['order' => $order, 'orderItems' => $orderItems])->render();

        // Return the rendered drawer card HTML as part of the AJAX response
        return response()->json(['drawerContent' => $drawerContent]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'items' => 'not_in:[]',
            'events' => 'not_in:[]',
            'playAreas' => 'not_in:[]',
        ]);

        if (!$request->filled('items') && !$request->filled('events') && !$request->filled('playAreas')) {
            return response()->json(['success' => false, 'message' => 'Please select at least 1 item.']);
        }

        $coupon = Coupon::where('code', $request->code)->where('status', 1)->first();

        /** @var \App\Models\User $user */
        $user = User::where('id', $request->userId)->first();

        $totalProductsPrice = 0;
        $totalItemsPrice = 0;
        $totalEventsPrice = 0;
        $totalPlayAreasPrice = 0;

        $cartItems = $request->input('items', []);
        foreach ($cartItems as $item) {
            $itemID = $item['id'];
            $quantity = $item['quantity'];
            $totalItemsPrice += Item::where('id', $itemID)->first()->price * $quantity;
        }

        $cartEvents = $request->input('events', []);
        foreach ($cartEvents as $event) {
            $itemID = $event['id'];
            $quantity = $event['quantity'];
            $totalEventsPrice += Event::where('id', $itemID)->first()->price * $quantity;
        }

        $cartPlayAreas = $request->input('playAreas', []);
        try {
            foreach ($cartPlayAreas as $playArea) {
                $date = $playArea['date'];
                $start = isset($playArea['start_time']) ? Carbon::parse($playArea['start_time']) : null;
                $end = isset($playArea['end_time']) ? Carbon::parse($playArea['end_time']) : null;

                if (!$start || !$end || !$date) {
                    throw new \Exception('Invalid Play Area: Missing start, end, or date.');
                }

                $durationInMinutes = $start->diffInMinutes($end);
                $durationInHours = $durationInMinutes / 60;
                $totalPlayAreasPrice += $playArea['quantity'] * $durationInHours * $playArea['price'];
            }
        } catch (\Exception $e) {
            // Log the specific error for debugging
            Log::error('Error calculating total play areas price: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Invalid Play Area: Missing start, end, or date.']);
        }

        $totalProductsPrice = round($totalItemsPrice + $totalEventsPrice + $totalPlayAreasPrice);

        $coupon = Coupon::where('code', $request->code)->where('status', 1)->first();

        if ($coupon) {
            $couponValidation = $this->getCustomerAppControllerData->validateCoupon($coupon, $totalProductsPrice, true) ?? true;
            $isCouponValid = $couponValidation['coupon_success'] ?? true;
            if (!$isCouponValid) {
                return response()->json(['success' => false, 'message' => $couponValidation['message']]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid Coupon!']);
        }
        $coupon_discount = 0;
        $totalApplicableItemsPrice = 0;
        $totalProductPriceAfterDiscount = $totalProductsPrice;
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

        return response()->json(['success' => true, 'data' => ['coupon_discount' =>  $coupon_discount, 'coupon' => $coupon]]);
    }
}
