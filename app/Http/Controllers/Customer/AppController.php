<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\PointsCreditEmail;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CustomerPointLog;
use App\Models\Event;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemRating;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PlayArea;
use App\Models\Point;
use App\Models\Rating;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Berkayk\OneSignal\OneSignalFacade;
use Carbon\Carbon;
use Coderflex\LaravelTicket\Models\Category;
use Coderflex\LaravelTicket\Models\Ticket;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalCustomerCount = User::role('customer')->count();
        $totalItemsCount = Item::where('status', 1)->where('approve', 1)->count();
        $totalVendorsCount = Vendor::where('approve', 1)->count();
        $topTenVendors = Vendor::where('approve', 1)->latest()->take(10)->get();
        $topFourItems = Item::where('status', 1)->where('approve', 1)->latest()->take(4)->get();
        return view("pages.dashboard.customer.dashboard", compact("totalCustomerCount", 'totalItemsCount', 'totalVendorsCount', 'topFourItems', 'topTenVendors'));
    }

    public function foodItemIndex(Request $request)
    {
        $items = Item::whereHas('vendor', function ($query) {
            $query->where('approve', 1);
        })
            ->where('status', 1)
            ->where('approve', 1)
            ->get();
        $vendors = Vendor::all();
        $categories = ItemCategory::all();
        $settings = Setting::first();
        if (auth()->check()) {
            $point_data = Point::first();
            $coupons = Coupon::where('status', 1)->where('approved', 1)->get();
            $coupons = $coupons->filter(function ($coupon) {
                $result = $this->validateCoupon($coupon, null, true);
                return $result['coupon_success'] ?? true;
            })->values();
            $this->storeInCartFromSession($request);
            $this->validateCartData();
            $data = $this->getCartData($request);
            return view('pages.items.customer.food-items', compact(['items', 'point_data', 'coupons', 'vendors', 'settings', 'categories', 'data']));
        } else {
            $this->validateCartData();
            $sessionData = $this->getSessionData($request);
            return view('pages.items.customer.guest-food-items', compact('items', 'sessionData', 'vendors', 'settings', 'categories'));
        }
    }

    public function playAreaIndex(Request $request)
    {
        $playAreas = PlayArea::where('status', 1)->get();
        $settings = Setting::first();
        if (auth()->check()) {
            $user = Auth::user();
            $point_data = Point::first();
            $coupons = Coupon::where('status', 1)->where('approved', 1)->get();
            $coupons = $coupons->filter(function ($coupon) {
                $result = $this->validateCoupon($coupon, null, true);
                return $result['coupon_success'] ?? true;
            })->values();
            $points = $user->point->points;
            $this->storeInCartFromSession($request);
            $this->validateCartData();
            $data = $this->getCartData($request);
            return view('pages.play-area.customer.play-areas', compact('playAreas', 'points', 'settings', 'point_data', 'coupons', 'data'));
        } else {
            $this->validateCartData();
            $sessionData = $this->getSessionData($request);
            return view('pages.play-area.customer.guest-play-areas', compact('sessionData', 'playAreas', 'settings'));
        }
    }

    public function eventIndex(Request $request)
    {
        $events = Event::where('status', 1)
            ->where('start_date', '>=', Carbon::now())
            ->get();
        $settings = Setting::first();
        if (auth()->check()) {
            $user = Auth::user();
            $points = $user->point->points;
            $point_data = Point::first();
            $coupons = Coupon::where('status', 1)->where('approved', 1)->get();
            $coupons = $coupons->filter(function ($coupon) {
                $result = $this->validateCoupon($coupon, null, true);
                return $result['coupon_success'] ?? true;
            })->values();
            $this->storeInCartFromSession($request);
            $this->validateCartData();
            $data = $this->getCartData($request);
            return view('pages.event.customer.events', compact('events', 'points', 'point_data', 'settings', 'coupons', 'data'));
        } else {
            $this->validateCartData();
            $sessionData = $this->getSessionData($request);
            return view('pages.event.customer.guest-events', compact('sessionData', 'events', 'settings'));
        }
    }

    public function contactUsIndex()
    {
        $settings = Setting::first();
        return view('pages.contact-us.customer.contact-us', compact('settings'));
    }

    public function contactUsSendMail(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_no' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $data['user_message'] = $data['message'];
        unset($data['message']);

        Mail::send('emails.contact', $data, function ($message) use ($data) {
            $settings = Setting::first();
            $message->to($settings->contact_us_email)
                ->subject('Contact Form Submission');
        });

        return back()->with('success', 'Your message has been sent successfully!');
    }

    public function storeSubscriptionId(Request $request)
    {
        // Validate incoming request if needed
        $request->validate([
            'subsId' => 'required|string', // Add any validation rules you need
        ]);

        // Get the authenticated user
        $user =  User::where('id', auth()->id())->first();

        if ($user) {
            // Update the authenticated user's onesignal_subs_id
            $user->onesignal_subs_id = $request->input('subsId');
            $user->save();

            // Return a response indicating success
            return response()->json(['message' => 'Subscription ID stored successfully']);
        } else {
            // Return an error response if the user is not authenticated
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    public function profile()
    {
        return view('pages.customers.customer.profile');
    }

    public function orders()
    {
        return view('pages.orders.customer.orders');
    }

    public function overview()
    {
        $categories = Category::where('is_visible', 1)->get();
        $tickets = Ticket::orderByRaw("CASE WHEN status = 'closed' THEN 1 ELSE 0 END")->where('user_id', auth()->id())->orWhere('assigned_to', auth()->id())->orderBy('updated_at', 'desc')->get();
        $pointLogs = CustomerPointLog::where('user_id', auth()->id())->orderByDesc('created_at')->get();
        return view('pages.customers.customer.profile', compact('pointLogs', 'tickets', 'categories'));
    }

    public function tickets()
    {
        $categories = Category::where('is_visible', 1)->get();
        $tickets = Ticket::orderByRaw("CASE WHEN status = 'closed' THEN 1 ELSE 0 END")->where('user_id', auth()->id())->orWhere('assigned_to', auth()->id())->orderBy('updated_at', 'desc')->get();
        return view('pages.customers.customer.tickets', compact(['tickets', 'categories']));
    }


    public function showOrder(Order $order)
    {
        return view('pages.customers.customer.order', compact('order'));
    }
    /**
     * Show the form for creating a new resource.
     */

    public function rateItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'order_id' => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
            'rating' => 'required|integer|between:1,5',
        ]);

        $existingRating = Rating::where('item_id', $request->item_id)
            ->where('order_id', $request->order_id)
            ->where('user_id', auth()->id())
            ->first();

        // If a rating already exists, return an error message
        if ($existingRating) {
            if ($existingRating->rating) {
                return response()->json(['success' => false, 'message' => 'You have already rated this item in this order.']);
            } else {
                $existingRating->rating = $request->rating;
                $existingRating->save();
            }
        } else {
            Rating::create([
                'item_id' => $request->item_id,
                'order_id' => $request->order_id,
                'order_item_id' => $request->order_item_id,
                'user_id' => auth()->id(),
                'rating' => $request->rating,
            ]);
        }

        // Calculate the new average rating for the item
        $newItemRating = Rating::where('item_id', $request->item_id)->avg('rating');

        // Update or create the item rating in the database
        $itemRating = ItemRating::updateOrCreate(
            ['item_id' => $request->item_id],
            ['rating' => $newItemRating]
        );

        // Get the vendor associated with the item
        $item = Item::findOrFail($request->item_id);
        $vendor_id = $item->vendor_id;

        // Calculate the new average rating for the vendor
        $newVendorRating = ItemRating::whereHas('item', function ($query) use ($vendor_id) {
            $query->where('vendor_id', $vendor_id);
        })->avg('rating');

        // Update or create the vendor rating in the database
        $vendorRating = VendorRating::updateOrCreate(
            ['vendor_id' => $vendor_id],
            ['rating' => $newVendorRating]
        );
        $point_data = Point::first();

        if ($point_data->review_points['status'] == 'active') {
            $customerPoint = auth()->user()->point;
            $customerPoint->points += $point_data->review_points['ratings_points'] ?? 0;
            if ($point_data->review_points['ratings_points'] > 0) {
                CustomerPointLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Rating',
                    'points' => $point_data->review_points['ratings_points'] ?? 0,
                    'details' => 'points added for rating an item',
                    'item_id' => $request->item_id,
                ]);
                Notification::create([
                    'user_id' => Auth::id(),
                    'message' => $point_data->review_points['alert_message'],
                ]);
                if (auth()->user()->onesignal_subs_id) {
                    $userId = auth()->user()->onesignal_subs_id;
                    $params = [];
                    $params['include_player_ids'] = [$userId];
                    $message = "Congratulation! You got " . $point_data->review_points['ratings_points'] . ' points';
                    $contents = [
                        "en" => $message,
                    ];
                    $params['contents'] = $contents;
                    OneSignalFacade::sendNotificationCustom(
                        $params
                    );
                }
                try {
                    if (auth()->user()->email) {
                        Mail::to(auth()->user()->email)->send(new PointsCreditEmail(auth()->user()->id, $point_data->review_points['ratings_points'], $point_data->review_points['alert_message']));
                    }
                } catch (\Exception $e) {
                    // Log the exception or perform any other error handling
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }
            $customerPoint->save();
        }

        return response()->json(['success' => true, 'message' => 'Rating submitted successfully']);
    }

    public function reviewItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'order_id' => 'required|exists:orders,id',
            'review' => 'required|string|max:255',
            'order_item_id' => 'required|exists:order_items,id',
        ]);

        $existingRating = Rating::where('item_id', $request->item_id)
            ->where('order_id', $request->order_id)
            ->where('user_id', auth()->id())
            ->first();

        // If a rating already exists, return an error message
        if ($existingRating) {
            if ($existingRating->review) {
                return response()->json(['success' => false, 'message' => 'You have already rated this item in this order.']);
            } else {
                $existingRating->review = $request->review;
                $existingRating->save();
            }
        } else {
            Rating::create([
                'item_id' => $request->item_id,
                'order_id' => $request->order_id,
                'user_id' => auth()->id(),
                'review' => $request->review,
                'order_item_id' => $request->order_item_id,
            ]);
        }

        $point_data = Point::first();
        if ($point_data->review_points['status'] == 'active') {
            $customerPoint = auth()->user()->point;
            $customerPoint->points += $point_data->review_points['review_points'] ?? 0;
            if ($point_data->review_points['review_points'] > 0) {
                CustomerPointLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Review',
                    'points' => $point_data->review_points['review_points'] ?? 0,
                    'details' => 'points added for review an item',
                    'item_id' => $request->item_id,
                ]);
                Notification::create([
                    'user_id' => Auth::id(),
                    'message' => $point_data->review_points['alert_message'],
                ]);
                if (auth()->user()->onesignal_subs_id) {
                    $userId = auth()->user()->onesignal_subs_id;
                    $params = [];
                    $params['include_player_ids'] = [$userId];
                    $message = "Congratulation! You got " . $point_data->review_points['review_points'] . ' points';
                    $contents = [
                        "en" => $message,
                    ];
                    $params['contents'] = $contents;
                    OneSignalFacade::sendNotificationCustom(
                        $params
                    );
                }
                try {
                    if (auth()->user()->email) {
                        Mail::to(auth()->user()->email)->send(new PointsCreditEmail(auth()->user()->id, $point_data->review_points['review_points'], $point_data->review_points['alert_message']));
                    }
                } catch (\Exception $e) {
                    // Log the exception or perform any other error handling
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }
            $customerPoint->save();
        }


        return response()->json(['success' => true, 'message' => 'Rating submitted successfully']);
    }


    public function settings()
    {
        return view('pages.customers.customer.settings');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'date_of_birth' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // max 2MB
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->customer->date_of_birth = $request->input('date_of_birth');

        if ($request->hasFile('avatar')) {
            if ($user->customer->avatar) {
                File::delete($user->customer->avatar);
            }
            $image = $request->file('avatar');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/users', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;
            $user->customer->avatar = $imagePath;
        }

        $user->save();
        $user->customer->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updateBirthday(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $request->validate([
            'date_of_birth' => 'required',
        ]);

        $user->customer->date_of_birth = $request->input('date_of_birth');
        $user->customer->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function getOrderDrawer(Request $request)
    {
        $orderId = $request->input('id');

        // Retrieve customer data based on customer ID
        $order = Order::find($orderId);

        if (!$orderId) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Render the drawer card view with customer data
        $drawerContent = view('layouts.customer.partials.ordersDrawerCard', ['order' => $order])->render();

        // Return the rendered drawer card HTML as part of the AJAX response
        return response()->json(['drawerContent' => $drawerContent]);
    }

    public function getItemsData(Request $request)
    {
        $itemId = $request->input('id');
        $sortOption = $request->input('sort', 'recent'); // Default to 'recent' if no sort option is provided

        // Retrieve item data based on item ID
        $item = Item::find($itemId);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        // Sort ratings based on the selected sort option
        switch ($sortOption) {
            case 'old':
                $item->ratings = $item->ratings()->orderBy('created_at', 'asc')->get();
                break;
            case 'lowest':
                $item->ratings = $item->ratings()->orderBy('rating', 'asc')->get();
                break;
            case 'highest':
                $item->ratings = $item->ratings()->orderBy('rating', 'desc')->get();
                break;
            case 'recent':
            default:
                $item->ratings = $item->ratings()->orderBy('created_at', 'desc')->get();
                break;
        }

        // Render the drawer card view with item data
        $drawerContent = view('pages.items.customer.partials.reviewModalContent', ['item' => $item, 'sortOption' => $sortOption])->render();

        // Return the rendered drawer card HTML as part of the AJAX response
        return response()->json(['drawerContent' => $drawerContent]);
    }

    public function getSessionData($request)
    {
        // Retrieve Items From Session
        $cartItemsData = $request->session()->get('cart.items', []);
        $itemIds = array_keys($cartItemsData);
        $cartItems = [];
        foreach ($itemIds as $itemId) {
            $item = Item::with('vendor')->where('status', 1)
                ->where('approve', 1)->find($itemId);
            if ($item) {
                $cartItems[] = [
                    'item' => $item,
                    'quantity' => $cartItemsData[$itemId]
                ];
            }
        }
        $cartItemsCollection = collect($cartItems);
        $itemTotalPrice = $cartItemsCollection->sum(function ($item) {
            return $item['quantity'] * $item['item']->price;
        });
        // Retrieve Play Areas From Session
        $cartPlayAreasData = $request->session()->get('cart.playAreas', []);
        $itemIds = array_keys($cartPlayAreasData);
        $cartPlayAreas = [];
        foreach ($itemIds as $itemId) {
            $playArea = PlayArea::where('status', 1)->find($itemId);
            if ($playArea) {
                $cartPlayAreas[] = [
                    'playArea' => $playArea,
                    'data' => $cartPlayAreasData[$itemId]
                ];
            }
        }
        $cartPlayAreaCollection = collect($cartPlayAreas);
        $playAreaTotalPrice = $cartPlayAreaCollection->sum(function ($playArea) {
            $start = isset($playArea['data']['start_time']) ? Carbon::parse($playArea['data']['start_time']) : null;
            $end = isset($playArea['data']['end_time']) ? Carbon::parse($playArea['data']['end_time']) : null;
            // Calculate the difference in minutes
            $durationInHours = 0;
            if ($start && $end) {
                $durationInMinutes = $start->diffInMinutes($end);
                $durationInHours = $durationInMinutes / 60;
            }
            return $playArea['data']['playersCount'] * $durationInHours * $playArea['playArea']->price;
        });
        // Retrieve Events From Session
        $cartEventData = $request->session()->get('cart.events', []);
        $itemIds = array_keys($cartEventData);
        $cartEvents = [];
        foreach ($itemIds as $itemId) {
            $event = Event::where('status', 1)->where('start_date', '>=', Carbon::now())->find($itemId);
            if ($event) {
                $cartEvents[] = [
                    'event' => $event,
                    'bookedSeatCount' => $cartEventData[$itemId]
                ];
            }
        }
        $cartEventsCollection = collect($cartEvents);
        $eventTotalPrice = $cartEventsCollection->sum(function ($event) {
            return $event['bookedSeatCount'] * $event['event']->price;
        });

        $totalProductPrice = $eventTotalPrice + $playAreaTotalPrice + $itemTotalPrice;
        $totalProductCount = count($cartItems) + count($cartPlayAreas) + count($cartEvents);

        $data = ['items' => $cartItems, 'totalProductPrice' => $totalProductPrice, 'playAreas' => $cartPlayAreas, 'events' => $cartEvents, 'totalProductCount' => $totalProductCount];

        return $data;
    }

    public function getNetTotalWithCharges($totalProductPrice)
    {
        $settings = Setting::first();
        $gstCharge = $totalProductPrice * ($settings->gst / 100);
        $sgtCharge = $totalProductPrice * ($settings->sgt / 100);
        $serviceTaxCharge = $totalProductPrice * ($settings->service_tax / 100);

        $netTotal = $totalProductPrice + $gstCharge + $sgtCharge + $serviceTaxCharge;

        return ['gstCharge' => $gstCharge, 'sgtCharge' => $sgtCharge, 'serviceTaxCharge' => $serviceTaxCharge, 'netTotal' => $netTotal, 'totalProductPrice' => $totalProductPrice];
    }

    public function storeInCartFromSession($request)
    {
        $user = Auth::user();
        $cartItemsData = $request->session()->get('cart.items', []);
        $cartPlayAreasData = $request->session()->get('cart.playAreas', []);
        $cartEventsData = $request->session()->get('cart.events', []);

        foreach ($cartItemsData as $itemId => $quantity) {
            /** @var \App\Models\User $user */
            $user->cartItems()->updateOrCreate(
                ['item_id' => $itemId],
                ['quantity' => $quantity]
            );
        }

        foreach ($cartPlayAreasData as $play_area_id => $data) {
            /** @var \App\Models\User $user */
            $user->cartItems()->updateOrCreate(
                ['play_area_id' => $play_area_id],
                [
                    'quantity' => $data['playersCount'],
                    'play_area_date' => $data['date'] ?? null,
                    'play_area_start_time' => $data['start_time'] ?? null,
                    'play_area_end_time' => $data['end_time'] ?? null,
                ]
            );
        }

        foreach ($cartEventsData as $event_id => $quantity) {
            /** @var \App\Models\User $user */
            $user->cartItems()->updateOrCreate(
                ['event_id' => $event_id],
                ['quantity' => $quantity],
            );
        }

        $inactiveItemIds = Item::where('status', 0)
            ->orWhere('approve', 0)
            ->pluck('id');

        $inactivePlayAreaIds = PlayArea::where('status', 0)
            ->pluck('id');

        $inactiveEventIds = Event::where('status', 0)
            ->pluck('id');

        Cart::where('user_id', auth()->id())
            ->where(function ($query) use ($inactiveItemIds, $inactivePlayAreaIds, $inactiveEventIds) {
                $query->where(function ($query) use ($inactiveItemIds) {
                    $query->whereIn('item_id', $inactiveItemIds)
                        ->whereNull('play_area_id')
                        ->whereNull('event_id');
                })->orWhere(function ($query) use ($inactivePlayAreaIds) {
                    $query->whereIn('play_area_id', $inactivePlayAreaIds)
                        ->whereNull('item_id')
                        ->whereNull('event_id');
                })->orWhere(function ($query) use ($inactiveEventIds) {
                    $query->whereIn('event_id', $inactiveEventIds)
                        ->whereNull('item_id')
                        ->whereNull('play_area_id');
                });
            })
            ->delete();

        $request->session()->forget('cart.items');
        $request->session()->forget('cart.events');
        $request->session()->forget('cart.playAreas');
    }

    public function getCartData($request)
    {
        $cartItems = Cart::where('user_id', auth()->id())->where('event_id', null)->where('play_area_id', null)->with(['item.vendor'])->get();

        $cartEvents = Cart::where('user_id', auth()->id())->where('item_id', null)->where('play_area_id', null)->with(['event'])->get();

        $cartPlayAreas = Cart::where('user_id', auth()->id())->where('item_id', null)->where('event_id', null)->with(['playArea'])->get();

        $totalItemCost = $cartItems->sum(function ($item) {
            return $item->quantity * $item->item->price;
        });

        $totalEventCost = $cartEvents->sum(function ($event) {
            return $event->quantity * $event->event->price;
        });

        $totalPlayAreaCost = $cartPlayAreas->sum(function ($playArea) {
            $start = isset($playArea->play_area_start_time) ? Carbon::parse($playArea->play_area_start_time) : null;
            $end = isset($playArea->play_area_end_time) ? Carbon::parse($playArea->play_area_end_time) : null;
            // Calculate the difference in minutes
            $durationInHours = 0;
            if ($start && $end) {
                $durationInMinutes = $start->diffInMinutes($end);
                $durationInHours = $durationInMinutes / 60;
            }
            return $playArea->quantity * $durationInHours * $playArea->playArea->price;
        });

        $totalProductPrice = $totalPlayAreaCost + $totalEventCost + $totalItemCost;
        $totalProductCount = count($cartItems) + count($cartPlayAreas) + count($cartEvents);
        $totalProductPriceAfterDiscount = $totalProductPrice;

        $coupon = Coupon::where('code', $request->code)->where('status', 1)->first();
        $coupon_discount = 0;
        $point_discount = 0;

        $data = ['cartItems' => $cartItems, 'totalProductPrice' => $totalProductPrice, 'cartPlayAreas' => $cartPlayAreas, 'cartEvents' => $cartEvents, 'totalProductCount' => $totalProductCount, 'couponDiscount' =>  $coupon_discount, 'pointDiscount' =>  $point_discount, 'coupon' => $coupon, 'totalProductPriceAfterDiscount' => $totalProductPriceAfterDiscount];

        if ($coupon) {
            $couponValidation = $this->validateCoupon($coupon, $totalProductPrice);
            $isCouponValid = $couponValidation['coupon_success'] ?? true;
            if (!$isCouponValid) {
                $data = array_merge($data, $couponValidation);
            } else {
                $couponCategoryIds = $coupon->itemCategories->pluck('id')->toArray();
                $couponItemIds = $coupon->items->pluck('id')->toArray();
                $totalApplicableItemsPrice = 0;

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
                } else {
                    $totalApplicableItemsPrice = $totalProductPrice;
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
                    $data['totalProductPriceAfterDiscount'] = $totalProductPriceAfterDiscount;
                    $data['couponDiscount'] = $coupon_discount;
                }
            }
        }
        if ($request->points > 0) {
            $pointValidation = $this->validatePoint($request->points, $totalProductCount, $totalEventCost, $totalPlayAreaCost);
            $isPointApplicable = $pointValidation['point_success'] ?? true;
            if (!$isPointApplicable) {
                $data = array_merge($data, $pointValidation);
            } else {
                $point_discount = $request->points * Point::first()->value;
                $data['totalProductPriceAfterDiscount'] = $totalProductPriceAfterDiscount - $point_discount;
                $data['pointDiscount'] = $point_discount;
            }
        }

        return $data;
    }

    public function validateCoupon($coupon, $totalProductPrice, $initialValidation = false)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $cartItems = Cart::where('user_id', auth()->id())->where('event_id', null)->where('play_area_id', null)->with(['item.vendor'])->get();
        if ($coupon) {
            if ($coupon->expire_date ? $coupon->expire_date  >= now() : true) {
                $couponCategoryIds = $coupon->itemCategories->pluck('id')->toArray();
                $couponItemIds = $coupon->items->pluck('id')->toArray();
                $isCouponApplicable = false;
                $totalApplicableItemsPrice = 0;
                if (!$initialValidation) {
                    if (!empty($couponCategoryIds) && !empty($couponItemIds)) {
                        foreach ($cartItems as $cartItem) {
                            $cartItemCategoryId = $cartItem->item->category_id;
                            $cartItemId = $cartItem->item->id;
                            if (in_array($cartItemCategoryId, $couponCategoryIds) && in_array($cartItemId, $couponItemIds)) {
                                $isCouponApplicable = true;
                                $totalApplicableItemsPrice += $cartItem->item->price * $cartItem->quantity;
                            }
                        }
                    } elseif (!empty($couponCategoryIds) && empty($couponItemIds)) {
                        foreach ($cartItems as $cartItem) {
                            $cartItemCategoryId = $cartItem->item->category_id;
                            if (in_array($cartItemCategoryId, $couponCategoryIds)) {
                                $isCouponApplicable = true;
                                $totalApplicableItemsPrice += $cartItem->item->price * $cartItem->quantity;
                            }
                        }
                    } elseif (empty($couponCategoryIds) && !empty($couponItemIds)) {
                        foreach ($cartItems as $cartItem) {
                            $cartItemId = $cartItem->item->id;
                            if (in_array($cartItemId, $couponItemIds)) {
                                $isCouponApplicable = true;
                                $totalApplicableItemsPrice += $cartItem->item->price * $cartItem->quantity;
                            }
                        }
                    }
                    if (!empty($couponCategoryIds) || !empty($couponItemIds)) {
                        if (!$isCouponApplicable) {
                            return ['coupon_success' => false, 'message' => 'This coupon is only valid for specific categories or items!'];
                        }
                        if ($totalApplicableItemsPrice < $coupon->minimum_amount) {
                            return ['coupon_success' => false, 'message' => 'Can not apply coupon! please buy more items!'];
                        }
                    }
                }
                if ($coupon->limit_type == 'global') {
                    $orders_count = Order::where('status', '!=', 'unpaid')->where('coupon_id', $coupon->id)->count();
                    if ($orders_count >= $coupon->limit && $coupon->limit != 0) {
                        return ['coupon_success' => false, 'message' => 'you cant not apply this coupon! Limit is over.'];
                    }
                } elseif ($coupon->limit_type == 'per_user') {
                    $couponUserIds = $coupon->users->pluck('id')->toArray();
                    if (!empty($couponUserIds)) {
                        if (!in_array($user->id, $couponUserIds)) {
                            return ['coupon_success' => false, 'message' => 'You are not eligible to apply this coupon!'];
                        }
                    }
                    $orders_count = Order::where('status', '!=', 'unpaid')->where('coupon_id', $coupon->id)->where('user_id', $user->id)->count();
                    if ($orders_count >= $coupon->limit && $coupon->limit != 0) {
                        return ['coupon_success' => false, 'message' => 'You cant not apply this coupon any more!'];
                    }
                } elseif ($coupon->limit_type == 'on_order') {
                    $couponUserIds = $coupon->users->pluck('id')->toArray();
                    if (!empty($couponUserIds)) {
                        if (!in_array($user->id, $couponUserIds)) {
                            return ['coupon_success' => false, 'message' => 'You are not eligible to apply this coupon!'];
                        }
                    }
                    $orders_count = Order::where('status', '!=', 'unpaid')->where('user_id', $user->id)->count();
                    if ($orders_count < $coupon->limit && $coupon->limit != 0) {
                        return ['coupon_success' => false, 'message' => 'You cant not apply this coupon any more!'];
                    }
                }
                if (!$initialValidation) {
                    if ($totalProductPrice < $coupon->minimum_amount) {
                        return ['coupon_success' => false, 'message' => 'Can not apply coupon! please buy more items!'];
                    }
                }
            } else {
                return ['coupon_success' => false, 'message' => 'you cant not apply this coupon!'];
            }
        }
    }

    public function validateEvent($eventId, $newQuantity)
    {
        $event = Event::where('id', $eventId)->where('status', 1)->where('start_date', '>=', Carbon::now())->first();
        if ($event) {
            $totalBooked = OrderItem::where('event_id', $event->id)
                ->where('status', '!=', 'unpaid')
                ->sum('quantity');

            if ($totalBooked + $newQuantity > $event->seats) {
                return ['success' => false, 'message' => 'Can not book ' . $event->title . '! Exceeds the seat limit.'];
            }
        } else {
            return ['success' => false, 'message' => 'Invalid Event! Maybe Expired'];
        }
    }

    public function validatePlayArea($playAreaId, $date, $startTime, $endTime, $quantity)
    {
        $playArea = PlayArea::where('id', $playAreaId)->where('status', 1)->first();
        if ($playArea) {
            if ($playArea->max_player < $quantity) {
                return ['success' => false, 'message' => 'Can not book ' . $playArea->title . '! Exceeds the booking limit.'];
            }

            // Convert times to a format that can be easily compared
            $startTime = Carbon::parse($startTime);
            $endTime = Carbon::parse($endTime);

            // Check if start time is after or equal to end time
            if ($startTime->greaterThanOrEqualTo($endTime)) {
                return ['success' => false, 'message' => 'Invalid booking times! Start time must be before end time.'];
            }

            // Fetch existing bookings for the given play area on the specified date
            $existingBookings = OrderItem::where('play_area_id', $playAreaId)
                ->whereDate('play_area_date', $date)
                ->where('status', '!=', 'unpaid')
                ->get();

            foreach ($existingBookings as $booking) {
                $existingStartTime = Carbon::parse($booking->play_area_start_time);
                $existingEndTime = Carbon::parse($booking->play_area_end_time);

                // Check if the new booking overlaps with any existing booking
                if (
                    $startTime->between($existingStartTime, $existingEndTime) ||
                    $endTime->between($existingStartTime, $existingEndTime) ||
                    $existingStartTime->between($startTime, $endTime) ||
                    $existingEndTime->between($startTime, $endTime)
                ) {
                    return ['success' => false, 'message' => 'Cannot book ' . $playArea->title . ' from ' . $startTime->format('H:i') . ' to ' . $endTime->format('H:i') . ' as it overlaps with an existing booking!'];
                }
            }
        } else {
            return ['success' => false, 'message' => 'Cannot book play area! Play area not found or inactive.'];
        }
    }


    public function validatePoint($points, $totalProductCount, $totalEventPrice, $totalPlayAreaPrice)
    {
        $pointData = Point::first();

        if ($totalProductCount < 1) {
            return ['point_success' => false, 'message' => "Please Select at least 1 item!"];
        }

        $cartItems = Cart::where('user_id', auth()->id())->where('event_id', null)->where('play_area_id', null)->with(['item.vendor'])->get();

        $totalItemPriceSupportingPoints = 0;
        $noItemsForSupportPoints = false;

        if ($totalEventPrice == 0 && $totalPlayAreaPrice == 0) {
            $noItemsForSupportPoints = true; // Assume no items support points initially

            foreach ($cartItems as $cartItem) {
                if ($cartItem->item->points_status == true) { // Assuming item has a 'points_status' property
                    $noItemsForSupportPoints = false; // Found an item that supports points
                    $totalItemPriceSupportingPoints += $cartItem->item->price * $cartItem->quantity; // Sum the total item price
                }
            }
        }

        $totalProductPrice = $totalItemPriceSupportingPoints + $totalEventPrice + $totalPlayAreaPrice;

        if ($noItemsForSupportPoints) {
            return ['point_success' => false, 'message' => "Cannot use points. No items in the cart support points."];
        }

        if ($totalProductPrice < $pointData->minimum_amount) {
            return ['point_success' => false, 'message' => "Please buy more items to use points!"];
        }

        $totalUseAblePoint = floor(($totalProductPrice * ($pointData->max_points / 100)) / $pointData->value);

        if ($totalUseAblePoint < $points) {
            return ['point_success' => false, 'message' => "Sorry! You can only use " . $totalUseAblePoint . " Points"];
        }

        if ($points > auth()->user()->point->points) {
            return ['point_success' => false, 'message' => "Sorry! You cant hack!"];
        }
    }

    public function validateCartData()
    {
        // For Events
        $cartEvents = Cart::where('user_id', auth()->id())->where('item_id', null)->where('play_area_id', null)->with(['event'])->get();
        $sessionEvents = Session::get('cart.events', []);
        foreach ($cartEvents as $cartEvent) {
            $eventValidation = $this->validateEvent($cartEvent->event_id, $cartEvent->quantity);
            $eventValid = $eventValidation['success'] ?? true;
            if (!$eventValid) {
                $cartEvent->delete();
                $eventId = $cartEvent->event->id;
                unset($sessionEvents[$eventId]);
                Session::put('cart.events', $sessionEvents);
            }
        }
        foreach ($sessionEvents as $event_id => $quantity) {
            $eventValidation = $this->validateEvent($event_id, $quantity);
            $eventValid = $eventValidation['success'] ?? true;
            if (!$eventValid) {
                unset($sessionEvents[$event_id]);
                Session::put('cart.events', $sessionEvents);
            }
        }
    }
}
