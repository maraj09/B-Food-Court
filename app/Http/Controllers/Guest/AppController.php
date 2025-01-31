<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Customer\AppController as CustomerAppController;
use App\Models\Event;
use App\Models\Item;
use App\Models\PlayArea;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppController extends Controller
{

    protected $getCustomerAppControllerData;

    public function __construct(CustomerAppController $customerAppController)
    {
        $this->getCustomerAppControllerData = $customerAppController;
    }
    /**
     * Display a listing of the resource.
     */
    public function addItemToSession(Request $request)
    {
        $itemId = $request->input('itemId');
        $quantity = $request->input('quantity', 1); // Default quantity is 1

        $newItem = Item::with('vendor')->where('status', 1)
            ->where('approve', 1)->find($itemId);

        if ($newItem) {
            // Retrieve current cart items from session
            $cartItems = $request->session()->get('cart.items', []);

            // Check if the item ID already exists in the cart
            if (array_key_exists($itemId, $cartItems)) {
                // If the item exists, update its quantity
                $cartItems[$itemId] += $quantity;
            } else {
                // If the item doesn't exist, add it with the specified quantity
                $cartItems[$itemId] = $quantity;
            }

            // Update the cart items in the session
            $request->session()->put('cart.items', $cartItems);

            $updatedData = $this->getUpToDateData($request);

            return response()->json(['success' => true, 'data' => $updatedData, 'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['sessionData']['totalProductPrice'])]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Item not found'
            ]);
        }
    }
    /**
     * Show the form for creating a new resource.
     */

    public function updateEventQuantity(Request $request)
    {
        $itemId = $request->input('itemId');
        $eventId = $request->input('eventId');
        $playAreaId = $request->input('playAreaId');
        $newQuantity = $request->input('quantity');
        $itemTotal = null;
        $cartPerPlayAreaTotalPrice = null;
        $eventTotal = null;
        if ($eventId) {
            // Retrieve current cart items from session
            $cartEvents = $request->session()->get('cart.events', []);
            if (isset($cartEvents[$eventId])) {
                $cartEvents[$eventId] = $newQuantity;
                $request->session()->put('cart.events', $cartEvents);
                $eventTotal = Event::where('id', $eventId)->first()->price * $newQuantity;
            } else {
                return response()->json(['success' => false, 'error' => 'Event not found in cart']);
            }
        } elseif ($playAreaId) {
            // Retrieve current cart items from session
            $cartPlayAreas = $request->session()->get('cart.playAreas', []);
            if (isset($cartPlayAreas[$playAreaId])) {
                $cartPlayAreas[$playAreaId]['playersCount'] = $newQuantity;
                $request->session()->put('cart.playAreas', $cartPlayAreas);
                $sessionPlayArea = $cartPlayAreas[$playAreaId];
                $playArea = PlayArea::where('id', $playAreaId)->first();

                $start = isset($sessionPlayArea['start_time']) ? Carbon::parse($sessionPlayArea['start_time']) : null;
                $end = isset($sessionPlayArea['end_time']) ? Carbon::parse($sessionPlayArea['end_time']) : null;
                // Calculate the difference in minutes
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
                    $pricePerHour = $playArea->price;

                    // Get the number of players
                    $playersCount = $sessionPlayArea['playersCount'] ?? 1;

                    // Calculate the total price
                    $cartPerPlayAreaTotalPrice = round($pricePerHour * $durationInHours * $playersCount);
                }
            } else {
                return response()->json(['success' => false, 'error' => 'Play Area not found in cart']);
            }
        } elseif ($itemId) {
            // Retrieve current cart items from session
            $cartItems = $request->session()->get('cart.items', []);
            if (isset($cartItems[$itemId])) {
                $cartItems[$itemId] = $newQuantity;
                $request->session()->put('cart.items', $cartItems);
                $itemTotal = Item::where('id', $itemId)->first()->price * $newQuantity;
            } else {
                return response()->json(['success' => false, 'error' => 'Play Area not found in cart']);
            }
        }

        if ($itemTotal != null || $cartPerPlayAreaTotalPrice != null || $eventTotal != null) {
            $updatedData = $this->getUpToDateData($request);
            return response()->json([
                'success' => true,
                'itemTotal' => $itemTotal,
                'cartPerPlayAreaTotalPrice' => $cartPerPlayAreaTotalPrice,
                'eventTotal' => $eventTotal,
                'data' => $updatedData,
                'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['sessionData']['totalProductPrice'])
            ]);
        }
    }

    public function removeItem(Request $request)
    {
        $cartItemId = $request->input('cartItemId');
        $playAreaId = $request->input('playAreaId');
        $eventId = $request->input('eventId');

        // Retrieve current cart items from session
        $cartItems = $request->session()->get('cart.items', []);
        $playAreas = $request->session()->get('cart.playAreas', []);
        $events = $request->session()->get('cart.events', []);

        // Check for item existence and remove if found
        $cartItemRemoved = false;
        $playAreaRemoved = false;
        $eventRemoved = false;

        if ($cartItemId && isset($cartItems[$cartItemId])) {
            unset($cartItems[$cartItemId]);
            $request->session()->put('cart.items', $cartItems);
            $cartItemRemoved = true;
        }

        if ($playAreaId && isset($playAreas[$playAreaId])) {
            unset($playAreas[$playAreaId]);
            $request->session()->put('cart.playAreas', $playAreas);
            $playAreaRemoved = true;
        }

        if ($eventId && isset($events[$eventId])) {
            unset($events[$eventId]);
            $request->session()->put('cart.events', $events);
            $eventRemoved = true;
        }

        if ($cartItemRemoved || $playAreaRemoved || $eventRemoved) {
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Item not found in cart'
            ]);
        }

        $updatedData = $this->getUpToDateData($request);

        return response()->json([
            'success' => true,
            'data' => $updatedData,
            'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['sessionData']['totalProductPrice'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function clearItems(Request $request)
    {
        $request->session()->forget('cart.items');
        $request->session()->forget('cart.playAreas');
        $request->session()->forget('cart.events');

        return redirect()->back()->with('success', 'Cart Cleared successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function bookPlayArea(Request $request)
    {
        $playAreaId = $request->input('playAreaId');
        $playersCount = $request->input('playerCount', 1);
        // Retrieve current cart items from session
        $cartItems = $request->session()->get('cart.playAreas', []);

        // Check if the item ID already exists in the cart
        if (array_key_exists($playAreaId, $cartItems)) {
            return response()->json(['success' => false, 'message' => 'Play Area already added!']);
        } else {
            // If the item doesn't exist, add it with the specified quantity
            $cartItems[$playAreaId] = ['playersCount' => $playersCount];
        }

        // Update the cart items in the session
        $request->session()->put('cart.playAreas', $cartItems);

        $updatedData = $this->getUpToDateData($request);

        return response()->json(['success' => true, 'data' => $updatedData, 'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['sessionData']['totalProductPrice'])]);
    }

    public function bookEvent(Request $request)
    {
        $eventId = $request->input('eventId');
        $bookedSeatCount = $request->input('eventBookedSeatCount', 1);
        // Retrieve current cart items from session
        $cartEvents = $request->session()->get('cart.events', []);

        // Check if the item ID already exists in the cart
        if (array_key_exists($eventId, $cartEvents)) {
            return response()->json(['success' => false, 'message' => 'Event already added!']);
        } else {
            // If the item doesn't exist, add it with the specified quantity
            $eventValidation = $this->getCustomerAppControllerData->validateEvent($eventId, $bookedSeatCount);
            $eventValid = $eventValidation['success'] ?? true;
            if (!$eventValid) {
                return response()->json($eventValidation);
            }
            $cartEvents[$eventId] = $bookedSeatCount;
        }

        // Update the cart items in the session
        $request->session()->put('cart.events', $cartEvents);

        $updatedData = $this->getUpToDateData($request);

        return response()->json(['success' => true, 'data' => $updatedData, 'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['sessionData']['totalProductPrice'])]);
    }

    public function updatePlayAreaDateTime(Request $request)
    {
        $type = $request->input('type');
        $value = $request->input('value');

        // Retrieve the play areas from the session
        $cartPlayAreas = $request->session()->get('cart.playAreas', []);
        $playAreaId = $request->input('playAreaId');

        if (isset($cartPlayAreas[$playAreaId])) {
            // Update the specific field
            $cartPlayAreas[$playAreaId][$type] = $value;

            // Save the updated play areas back into the session
            $request->session()->put('cart.playAreas', $cartPlayAreas);
            $cartPlayAreas = $request->session()->get('cart.playAreas', []);
            $sessionPlayArea = $cartPlayAreas[$playAreaId];
            $playArea = PlayArea::where('id', $playAreaId)->first();

            $start = isset($sessionPlayArea['start_time']) ? Carbon::parse($sessionPlayArea['start_time']) : null;
            $end = isset($sessionPlayArea['end_time']) ? Carbon::parse($sessionPlayArea['end_time']) : null;

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
                $pricePerHour = $playArea->price;

                // Get the number of players
                $playersCount = $sessionPlayArea['playersCount'] ?? 1;

                // Calculate the total price
                $cartPerPlayAreaTotalPrice = round($pricePerHour * $durationInHours * $playersCount);
            }

            $updatedData = $this->getUpToDateData($request);
            return response()->json(['success' => true, 'cartPlayAreas' => $cartPlayAreas, 'durationFormatted' => $durationFormatted, 'cartPerPlayAreaTotalPrice' => $cartPerPlayAreaTotalPrice, 'data' => $updatedData, 'chargesWithNetTotal' => $this->getCustomerAppControllerData->getNetTotalWithCharges($updatedData['sessionData']['totalProductPrice'])]);
        } else {
            return response()->json(['success' => false, 'error' => 'Play Area not found in cart']);
        }
    }

    public function getUpToDateData($request)
    {
        $sessionData = $this->getCustomerAppControllerData->getSessionData($request);
        return ['sessionData' => $sessionData];
    }
}
