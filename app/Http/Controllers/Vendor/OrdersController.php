<?php

namespace App\Http\Controllers\Vendor;

use App\Events\OrderStatusChangeEvent;
use App\Http\Controllers\Controller;
use App\Mail\OrderInvoiceEmail;
use App\Models\AdminWallet;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Rating;
use App\Models\VendorBank;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Razorpay\Api\Api;
use stdClass;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendorId = Auth::user()->vendor->id;
        $orders = OrderItem::whereHas('item.vendor', function ($query) use ($vendorId) {
            $query->where('id', $vendorId);
        })->where('status', '!=', 'unpaid')->orderBy('created_at', 'desc')->get()->groupBy('order_id');

        return view('pages.orders.vendor.orders', compact(['orders']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getOrderDrawer(Request $request)
    {
        $vendorId = Auth::user()->vendor->id;
        $orderId = $request->input('id');

        // Retrieve customer data based on customer ID
        $order = Order::find($orderId);

        $orderItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->where('order_id', $order->id)->where('status', '!=', 'unpaid')->get();

        if (!$orderId) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Render the drawer card view with customer data
        $drawerContent = view('pages.orders.vendor.partials.orderDrawerCard', ['order' => $order, 'orderItems' => $orderItems])->render();

        // Return the rendered drawer card HTML as part of the AJAX response
        return response()->json(['drawerContent' => $drawerContent]);
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
    public function show(Order $order)
    {
        $vendorId = Auth::user()->vendor->id;
        $orderItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->where('order_id', $order->id)->get();
        $subTotal = 0;
        foreach ($orderItems as $orderItem) {
            $subTotal += $orderItem->item->price * $orderItem->quantity;
        }
        return view('pages.orders.vendor.show', compact(['subTotal', 'order']));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request)
    {
        // Retrieve search query from the request
        $searchQuery = $request->input('search');

        // Perform the search query to retrieve orders
        $vendorId = Auth::user()->vendor->id;
        $orders = OrderItem::whereHas('item.vendor', function ($query) use ($vendorId) {
            $query->where('id', $vendorId);
        })
            ->where(function ($query) use ($searchQuery) {
                $query->orWhereHas('order', function ($subQuery) use ($searchQuery) {
                    $subQuery->where('order_id', 'like', '%' . $searchQuery . '%');
                })
                    ->orWhereHas('item', function ($subQuery) use ($searchQuery) {
                        $subQuery->where('item_name', 'like', '%' . $searchQuery . '%');
                    });
            })
            ->get()
            ->groupBy('order_id');
        // Render the HTML content for the orders
        $allItems = OrderItem::whereHas('item.vendor', function ($query) use ($vendorId) {
            $query->where('id', $vendorId);
        })->whereIn('order_id', $orders->keys()->toArray())
            ->with(['order', 'item'])
            ->get()
            ->groupBy('order_id');
        $mergedOrders = collect();

        foreach ($orders as $orderId => $orderItems) {
            $allItemsForOrder = $allItems->get($orderId);
            if ($allItemsForOrder) {
                $mergedItems = $orderItems->merge($allItemsForOrder);
                $mergedItems = $mergedItems->sortBy('id');
                $mergedOrders->put($orderId, $mergedItems);
            } else {
                $mergedOrders->put($orderId, $orderItems);
            }
        }
        $htmlContent = view('pages.orders.vendor.partials.orders_table', ['orders' => $mergedOrders])->render();
        return response()->json(['htmlContent' => $htmlContent]);
    }
    public function updateItemStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $item = OrderItem::findOrFail($id);
        $order = Order::where('id', $item->order_id)->first();

        $item->status = $status;
        $item->save();

        $orderStatus = 'pending';

        // Assume all items are delivered until proven otherwise
        $allItemsDelivered = true;
        $allItemsRejected = true;

        // Iterate through all the order items
        foreach ($order->orderItems as $orderItem) {
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

        $response = [
            'success' => true,
            'message' => 'Status updated successfully.',
        ];

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

        foreach ($order->orderItems as $orderItem) {
            if ($orderItem->status == 'delivered') {
                $deliveredItemsCount++;
            } elseif ($orderItem->status == 'rejected') {
                $rejectedItemsCount++;
            }
        }

        $totalItemsCount = count($order->orderItems);

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
        return response()->json($response);
    }
}
