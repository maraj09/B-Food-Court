<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\Item;
use App\Models\Order;
use App\Models\PlayArea;
use App\Models\Point;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Customer\AppController as CustomerAppController;
use App\Models\OrderItem;

class CartController extends Controller
{

    protected $getCustomerAppControllerData;

    public function __construct(CustomerAppController $customerAppController)
    {
        $this->getCustomerAppControllerData = $customerAppController;
    }

    public function storeItemToCart(Request $request)
    {
        $itemId = $request->input('itemId');
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $existingCartItem = $user->cartItems()->where('item_id', $itemId)->first();

        if ($existingCartItem) {
            $existingCartItem->increment('quantity');
        } else {
            $user->cartItems()->create([
                'item_id' => $itemId,
                'quantity' => 1,
            ]);
        }
        $updatedData = $this->getUpToDateData($request);
        $couponValidation = $updatedData['cartData']['coupon_success'] ?? true;
        $responseJsonArray = [
            'success' => true,
            'coupon_success' => true,
            'point_success' => true,
            'data' => $updatedData,
            'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])
        ];
        if (!$couponValidation) {
            $responseJsonArray['coupon_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }
        $pointValidation = $updatedData['cartData']['point_success'] ?? true;
        if (!$pointValidation) {
            $responseJsonArray['point_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }

        return response()->json($responseJsonArray);
    }

    public function storePlayAreaToCart(Request $request)
    {
        $playAreaId = $request->input('playAreaId');
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $existingCartItem = $user->cartItems()->where('play_area_id', $playAreaId)->first();

        if ($existingCartItem) {
            $existingCartItem->increment('quantity');
        } else {
            $user->cartItems()->create([
                'play_area_id' => $playAreaId,
                'quantity' => 1,
            ]);
        }
        $updatedData = $this->getUpToDateData($request);
        $responseJsonArray = [
            'success' => true,
            'coupon_success' => true,
            'point_success' => true,
            'data' => $updatedData,
            'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])
        ];
        $couponValidation = $updatedData['cartData']['coupon_success'] ?? true;
        if (!$couponValidation) {
            $responseJsonArray['coupon_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }
        $pointValidation = $updatedData['cartData']['point_success'] ?? true;
        if (!$pointValidation) {
            $responseJsonArray['point_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }

        return response()->json($responseJsonArray);
    }

    public function storeEventToCart(Request $request)
    {
        $eventId = $request->input('eventId');
        $quantity = $request->input('eventBookedSeatCount', 1);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $existingCartItem = $user->cartItems()->where('event_id', $eventId)->first();

        $eventValidation = $this->getCustomerAppControllerData->validateEvent($eventId, $quantity);
        $eventValid = $eventValidation['success'] ?? true;
        if (!$eventValid) {
            return response()->json($eventValidation);
        }

        if ($existingCartItem) {
            $eventValidation = $this->getCustomerAppControllerData->validateEvent($eventId, $existingCartItem->quantity + $quantity);
            $eventValid = $eventValidation['success'] ?? true;
            if (!$eventValid) {
                return response()->json($eventValidation);
            }
            $existingCartItem->increment('quantity', $quantity);
        } else {
            $user->cartItems()->create([
                'event_id' => $eventId,
                'quantity' => $quantity,
            ]);
        }
        $updatedData = $this->getUpToDateData($request);
        $couponValidation = $updatedData['cartData']['coupon_success'] ?? true;
        $responseJsonArray = [
            'success' => true,
            'coupon_success' => true,
            'point_success' => true,
            'data' => $updatedData,
            'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])
        ];
        if (!$couponValidation) {
            $responseJsonArray['coupon_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }
        $pointValidation = $updatedData['cartData']['point_success'] ?? true;
        if (!$pointValidation) {
            $responseJsonArray['point_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }

        return response()->json($responseJsonArray);
    }

    public function getUpToDateData($request)
    {
        $cartData = $this->getCustomerAppControllerData->getCartData($request);
        return ['cartData' => $cartData];
    }

    public function removeFromCart(Request $request)
    {
        $item = Cart::find($request->input('productCartId'));
        $item->delete();

        $updatedData = $this->getUpToDateData($request);
        $couponValidation = $updatedData['cartData']['coupon_success'] ?? true;
        $responseJsonArray = [
            'success' => true,
            'coupon_success' => true,
            'point_success' => true,
            'data' => $updatedData,
            'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])
        ];
        if (!$couponValidation) {
            $responseJsonArray['coupon_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }
        $pointValidation = $updatedData['cartData']['point_success'] ?? true;
        if (!$pointValidation) {
            $responseJsonArray['point_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }
        return response()->json($responseJsonArray);
    }

    public function removeAllCart()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $user->cartItems()->delete();

        return redirect()->back()->with('success', 'Items removed from cart.');
    }

    public function updateEventQuantity(Request $request)
    {
        // Validate the request if needed
        $request->validate([
            'quantity' => 'required|numeric|min:1',
        ]);

        // Update the quantity in the database
        $cartProductId = $request->input('productCartId');
        $cartProduct = Cart::findOrFail($cartProductId);
        $productTotal = 0;
        if ($cartProduct->item_id) {
            $cartProduct->update(['quantity' => $request->input('quantity')]);
            $productTotal = $cartProduct->quantity * $cartProduct->item->price;
        } elseif ($cartProduct->play_area_id) {
            $cartProduct->update(['quantity' => $request->input('quantity')]);
            $start = isset($cartProduct->play_area_start_time) ? Carbon::parse($cartProduct->play_area_start_time) : null;
            $end = isset($cartProduct->play_area_end_time) ? Carbon::parse($cartProduct->play_area_end_time) : null;
            // Calculate the difference in minutes
            $durationInHours = 0;
            if ($start && $end) {
                $durationInMinutes = $start->diffInMinutes($end);
                $durationInHours = $durationInMinutes / 60;
            }
            $productTotal = $cartProduct->quantity * $cartProduct->playArea->price * $durationInHours;
        } elseif ($cartProduct->event_id) {
            $eventValidation = $this->getCustomerAppControllerData->validateEvent($cartProduct->event_id, $request->quantity);
            $eventValid = $eventValidation['success'] ?? true;
            if (!$eventValid) {
                return response()->json($eventValidation);
            }
            $cartProduct->update(['quantity' => $request->input('quantity')]);
            $productTotal = $cartProduct->quantity * $cartProduct->event->price;
        }
        $updatedData = $this->getUpToDateData($request);
        $couponValidation = $updatedData['cartData']['coupon_success'] ?? true;
        $responseJsonArray = [
            'success' => true,
            'coupon_success' => true,
            'point_success' => true,
            'productTotal' => $productTotal,
            'data' => $updatedData,
            'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])
        ];
        if (!$couponValidation) {
            $responseJsonArray['coupon_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }
        $pointValidation = $updatedData['cartData']['point_success'] ?? true;
        if (!$pointValidation) {
            $responseJsonArray['point_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }

        return response()->json($responseJsonArray);
    }

    public function removeCoupon(Request $request)
    {
        $updatedData = $this->getUpToDateData($request);
        $couponValidation = $updatedData['cartData']['coupon_success'] ?? true;
        if (!$couponValidation) {
            return response()->json(['coupon_success' => false, 'data' => $updatedData, 'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])]);
        }
        return response()->json([
            'success' => true,
            'data' => $updatedData,
            'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $updatedData = $this->getUpToDateData($request);
        $couponValidation = $updatedData['cartData']['coupon_success'] ?? true;
        if (!$couponValidation) {
            return response()->json(['success' => false, 'data' => $updatedData, 'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])]);
        }

        return response()->json([
            'success' => true,
            'data' => $updatedData,
            'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])
        ]);
    }

    public function searchItems(Request $request)
    {
        $searchQuery = $request->input('search');
        $vendorQuery = $request->input('vendor');
        $categoryQuery = $request->input('category');

        $query = Item::whereHas('vendor', function ($query) {
            $query->where('approve', 1);
        })->where('status', 1)->where('approve', 1);

        if ($searchQuery) {
            $query->where('item_name', 'like', "%$searchQuery%");
        }

        if ($vendorQuery && $vendorQuery !== 'all') {
            $query->where('vendor_id', $vendorQuery);
        }

        if ($categoryQuery && $categoryQuery !== 'all') {
            $query->where('category_id', $categoryQuery);
        }

        $filteredItems = $query->with(['vendor', 'category', 'itemRating'])->get();

        return response()->json($filteredItems);
    }

    public function searchProducts(Request $request)
    {
        $user = auth()->user();
        $searchQuery = $request->input('search');
        $categoryQuery = $request->input('category');

        $filteredItems = [];
        $filteredPlayAreas = [];
        $filteredEvents = [];

        // Check for item permission (assuming there's a permission for managing items)
        if ($user->hasPermissionTo('cashier-items-management')) {
            $itemQuery = Item::whereHas('vendor', function ($query) {
                $query->where('approve', 1);
            })->where('status', 1)->where('approve', 1);

            if ($searchQuery) {
                $itemQuery->where('item_name', 'like', "%$searchQuery%");
            }

            if ($categoryQuery && $categoryQuery !== 'all') {
                $itemQuery->where('category_id', $categoryQuery);
            }

            $filteredItems = $itemQuery->with(['vendor', 'category', 'itemRating'])->get();
        }

        // Check for play area permission
        if ($user->hasPermissionTo('cashier-play-area-management')) {
            $playAreaQuery = PlayArea::where('status', 1);

            if ($searchQuery) {
                $playAreaQuery->where('title', 'like', "%$searchQuery%");
            }

            $filteredPlayAreas = $playAreaQuery->get();
        }

        // Check for event permission
        if ($user->hasPermissionTo('cashier-events-management')) {
            $eventQuery = Event::where('status', 1)
                ->where('start_date', '>=', Carbon::now());

            if ($searchQuery) {
                $eventQuery->where('title', 'like', "%$searchQuery%");
            }

            $filteredEvents = $eventQuery->get();

            foreach ($filteredEvents as $event) {
                $totalQuantity = OrderItem::where('event_id', $event->id)
                    ->where('status', '!=', 'unpaid')
                    ->sum('quantity');
                $event->totalBooked = $totalQuantity;
            }
        }

        $response = [
            'items' => $filteredItems,
            'playAreas' => $filteredPlayAreas,
            'events' => $filteredEvents,
        ];

        return response()->json($response);
    }


    public function searchPlayArea(Request $request)
    {
        $search = $request->input('search', null);
        $range = $request->input('range', null);
        $startDate = null;
        $endDate = null;
        $startTime = null;
        $endTime = null;

        // Extract date and time from the range if provided
        if ($range) {
            [$start, $end] = explode(' - ', $range);
            try {
                $format = 'n/j h:i A';
                $startDate = Carbon::createFromFormat($format, trim($start))->format('Y-m-d');
                $endDate = Carbon::createFromFormat($format, trim($end))->format('Y-m-d');
                $startTime = Carbon::createFromFormat($format, trim($start))->format('H:i:s');
                $endTime = Carbon::createFromFormat($format, trim($end))->format('H:i:s');
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to parse date-time range: ' . $e->getMessage()], 400);
            }
        }

        // Query to find available play areas that are not booked during the specified date and time range
        $availablePlayAreas = PlayArea::query();

        if ($search) {
            $availablePlayAreas->where('title', 'like', '%' . $search . '%');
        }

        if ($startDate && $endDate && $startTime && $endTime) {
            $availablePlayAreas->whereDoesntHave('orderPlayAreas', function ($query) use ($startDate, $endDate, $startTime, $endTime) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Check if the play area booking overlaps the selected date range
                    $q->whereBetween('play_area_date', [$startDate, $endDate]);
                })->where(function ($q) use ($startTime, $endTime) {
                    // Check time overlaps on the same booked date
                    $q->whereBetween('play_area_start_time', [$startTime, $endTime])
                        ->orWhereBetween('play_area_end_time', [$startTime, $endTime])
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            $q->where('play_area_start_time', '<=', $startTime)
                                ->where('play_area_end_time', '>=', $endTime);
                        });
                });
            });
        }

        // Fetch and return the available play areas
        $availablePlayAreas = $availablePlayAreas->get();

        return response()->json($availablePlayAreas);
    }

    public function searchEvents(Request $request)
    {
        $searchQuery = $request->input('search');
        $date = $request->input('date');

        $query = Event::where('status', 1)->where('start_date', '>=', Carbon::now())->where('title', 'like', "%$searchQuery%");

        if ($date) {
            $query->whereDate('start_date', '=', $date);
        }

        $filteredItems = $query->get();

        // Calculate total quantity for each event
        foreach ($filteredItems as $event) {
            $totalQuantity = OrderItem::where('event_id', $event->id)
                ->where('status', '!=', 'unpaid')
                ->sum('quantity');
            $event->totalBooked = $totalQuantity;
        }

        // Return the filtered items as JSON response
        return response()->json($filteredItems);
    }


    public function filterByVendor(Request $request)
    {
        $vendorQuery = $request->input('vendor');
        $searchQuery = $request->input('search');

        if ($vendorQuery == 'all') {
            $filteredItems = Item::whereHas('vendor', function ($query) {
                $query->where('approve', 1);
            })->where('status', 1)->where('approve', 1)->where('item_name', 'like', "%$searchQuery%")->with(['vendor', 'category'])->get();
        } else {
            $filteredItems = Item::whereHas('vendor', function ($query) {
                $query->where('approve', 1);
            })->where('status', 1)->where('approve', 1)->where('vendor_id', $vendorQuery)->where('item_name', 'like', "%$searchQuery%")->with(['vendor', 'category'])->get();
        }
        return response()->json($filteredItems);
    }

    public function updatePlayAreaDateTime(Request $request)
    {
        $type = $request->input('type');
        $value = $request->input('value');

        $playAreaCartId = $request->input('playAreaCartId');
        $cartPlayArea = Cart::findOrFail($playAreaCartId);
        // Retrieve the play areas from the session
        if ($type == 'date') {
            $cartPlayArea->update(['play_area_date' => $value]);
        } elseif ($type == 'start_time') {
            $cartPlayArea->update(['play_area_start_time' => $value]);
        } elseif ($type == 'end_time') {
            $cartPlayArea->update(['play_area_end_time' => $value]);
        }

        $start = isset($cartPlayArea->play_area_start_time) ? Carbon::parse($cartPlayArea->play_area_start_time) : null;
        $end = isset($cartPlayArea->play_area_end_time) ? Carbon::parse($cartPlayArea->play_area_end_time) : null;

        $durationFormatted = '0H';
        $cartPerPlayAreaTotalPrice = '0';

        if ($start && $end) {
            $duration = $start->diff($end);

            // Format the duration in hours and minutes conditionally
            $durationFormatted = $duration->h . 'H';
            if ($duration->i > 0) {
                $durationFormatted .= ' ' . $duration->i . 'Min';
            }

            // Calculate the difference in minutes
            $durationInMinutes = $start->diffInMinutes($end);

            // Convert minutes to hours (including fractions)
            $durationInHours = $durationInMinutes / 60;

            // Get the price per hour from the play area
            $pricePerHour = $cartPlayArea->playArea->price;

            // Get the number of players
            $playersCount = $cartPlayArea->quantity ?? 1;

            // Calculate the total price
            $cartPerPlayAreaTotalPrice = round($pricePerHour * $durationInHours * $playersCount);
        }

        $couponValidation = $updatedData['cartData']['coupon_success'] ?? true;
        $updatedData = $this->getUpToDateData($request);

        $responseJsonArray = [
            'success' => true,
            'coupon_success' => true,
            'point_success' => true,
            'data' => $updatedData,
            'durationFormatted' => $durationFormatted,
            'cartPerPlayAreaTotalPrice' => $cartPerPlayAreaTotalPrice,
            'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])
        ];
        if (!$couponValidation) {
            $responseJsonArray['coupon_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }
        $pointValidation = $updatedData['cartData']['point_success'] ?? true;
        if (!$pointValidation) {
            $responseJsonArray['point_success'] = false;
            $responseJsonArray['data'] = $updatedData;
        }

        return response()->json($responseJsonArray);
    }

    public function updateCartPoint(Request $request)
    {
        $updatedData = $this->getUpToDateData($request);
        $pointValidation = $updatedData['cartData']['point_success'] ?? true;
        if (!$pointValidation) {
            return response()->json(['success' => false, 'data' => $updatedData, 'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])]);
        }

        return response()->json([
            'success' => true,
            'data' => $updatedData,
            'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['cartData']['totalProductPriceAfterDiscount'])
        ]);
    }
}
