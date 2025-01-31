<?php

namespace App\Console\Commands;

use App\Mail\PointsCreditEmail;
use App\Models\AdminWallet;
use App\Models\AdminWalletLog;
use App\Models\CustomerPointLog;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Point;
use App\Models\Setting;
use App\Models\User;
use App\Models\VendorBank;
use App\Models\VendorBankLog;
use Berkayk\OneSignal\OneSignalFacade;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;

class orderChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:order-checker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // Calculate the timestamp 5 minutes ago
        $fiveMinutesAgo = now()->subMinutes(300);


        // Query for orders created within the last 5 minutes and with 'unpaid' status
        $orders = Order::where('status', 'unpaid')
            ->where('created_at', '>=', $fiveMinutesAgo)
            ->where('created_at', '<=', now())->orderByDesc('created_at')
            ->get();

        $api_key = env('RAZORPAY_KEY');
        $api_secret = env('RAZORPAY_SECRET');
        $api = new Api($api_key, $api_secret);
        $settings = Setting::first();

        foreach ($orders as $order) {
            $razorpayOrder = $api->order->fetch($order->razorpay_order_id);
            $user = User::where('id', $order->user_id)->first();
            if ($razorpayOrder->status == 'paid') {
                $rzOrderPayment = $api->order->fetch($order->razorpay_order_id)->payments();
                foreach ($rzOrderPayment->items as $item) {
                    if ($item->status == 'captured') {
                        $order->razorpay_payment_id = $item->id;
                        $order->payment_method =  $item->method;
                        $order->status = 'paid';
                        $order->update();

                        $totalPrice = $order->order_amount / 100;
                        $orderItems = OrderItem::where('order_id', $order->id)->get();

                        foreach ($orderItems as $orderItem) {
                            $orderItem->status = 'pending';
                            $orderItem->save();
                            if ($orderItem->play_area_id) {
                                $start = isset($orderItem->play_area_start_time) ? Carbon::parse($orderItem->play_area_start_time) : null;
                                $end = isset($orderItem->play_area_end_time) ? Carbon::parse($orderItem->play_area_end_time) : null;
                                // Calculate the difference in minutes
                                $durationInHours = 0;
                                if ($start && $end) {
                                    $durationInMinutes = $start->diffInMinutes($end);
                                    $durationInHours = $durationInMinutes / 60;
                                }
                                $proportion = ($orderItem->price *  $orderItem->quantity * $durationInHours) / $totalPrice;
                            } else {
                                $proportion = ($orderItem->price *  $orderItem->quantity) / $totalPrice;
                            }
                            $earnings = $proportion * $order->order_amount / 100;
                            $adminWallet = AdminWallet::first();
                            if ($orderItem->item_id) {
                                $vendorId = $orderItem->item->vendor_id;
                                $vendorBank = VendorBank::where('vendor_id', $vendorId)->first();
                                // Calculate the vendor's earnings (based on vendor commission)
                                $adminEarnings = $earnings * ($orderItem->item->vendor->commission / 100);

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
                                    $gstCost = number_format($orderItem->price * $orderItem->quantity * ($settings->gst / 100), 2);
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
                                    $sgtCost = number_format($orderItem->price * $orderItem->quantity * ($settings->sgt / 100), 2);
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
                            }
                        }
                        if ($orderItem->item_id) {
                            $uniqueVendorIds = [];
                            foreach ($orderItems as $orderItem) {
                                $vendorId = $orderItem->item->vendor->user->id;
                                if (!in_array($vendorId, $uniqueVendorIds)) {
                                    $uniqueVendorIds[] = $vendorId;
                                }
                            }
                            $url = '/vendor/orders/' . $order->id;
                            $message = 'New order from your store!';
                            foreach ($uniqueVendorIds as $vendorId) {
                                $vendorUser =  User::where('id', $vendorId)->first();
                                if ($vendorUser && $vendorUser->onesignal_subs_id) {
                                    $vendorUserId = $vendorUser->onesignal_subs_id;
                                    $params = [];
                                    $params['include_player_ids'] = [$vendorUserId];
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

                        if ($settings->gst_admin) {
                            $adminWallet->balance += $order->gst_amount;
                            $adminWallet->total_earning += $order->gst_amount;
                            AdminWalletLog::create([
                                'action' => 'deposit',
                                'amount' => $order->gst_amount
                            ]);
                            $adminWallet->save();
                        }

                        if ($settings->sgt_admin) {
                            $adminWallet->balance += $order->sgt_amount;
                            $adminWallet->total_earning += $order->sgt_amount;
                            AdminWalletLog::create([
                                'action' => 'deposit',
                                'amount' => $order->sgt_amount
                            ]);
                            $adminWallet->save();
                        }

                        if ($order->service_tax > 0) {
                            $adminWallet->balance += $order->service_tax;
                            $adminWallet->total_earning += $order->service_tax;
                            AdminWalletLog::create([
                                'action' => 'deposit',
                                'amount' => $order->service_tax
                            ]);
                            $adminWallet->save();
                        }

                        $points = $user->point->points;
                        $point_data = Point::first();
                        $orderPointStatus = $point_data->order_points['status'];

                        if ($order->discount != 0) {
                            $user->point->points = $orderPointStatus == 'active' ? $point_data->order_points['points'] : 0;
                        } else {
                            if ($orderPointStatus == 'active') {
                                $user->point->points = $user->point->points + $point_data->order_points['points'];
                            }
                        }
                        if ($point_data->order_points['points'] > 0 && $orderPointStatus == 'active') {
                            CustomerPointLog::create([
                                'user_id' => $user->id,
                                'action' => 'Order',
                                'points' => $point_data->order_points['points'] ?? 0,
                                'order_id' => $order->id,
                                'details' => 'Points added of Order Placed'
                            ]);
                            Notification::create([
                                'user_id' =>  $user->id,
                                'message' => $point_data->order_points['alert_message'],
                            ]);
                            try {
                                if ($user->email) {
                                    Mail::to($user->email)->send(new PointsCreditEmail($user->id, $point_data->order_points['points'], $point_data->order_points['alert_message']));
                                }
                            } catch (\Exception $e) {
                                // Log the exception or perform any other error handling
                                Log::error('Failed to send email: ' . $e->getMessage());
                            }
                        }
                        if ($order->discount != 0) {
                            CustomerPointLog::create([
                                'user_id' => $user->id,
                                'action' => 'Redeem',
                                'points' => $points,
                                'order_id' => $order->id,
                                'details' => 'Points redeem on Order'
                            ]);
                        }

                        if ($user->onesignal_subs_id) {
                            $userId = $user->onesignal_subs_id;
                            $params = [];
                            $params['include_player_ids'] = [$userId];
                            $params['url'] = '/orders/' . $order->id;
                            $message = "You order is placed!";
                            $contents = [
                                "en" => $message,
                            ];
                            $params['contents'] = $contents;
                            OneSignalFacade::sendNotificationCustom(
                                $params
                            );
                        }
                        $user->point->save();
                    }
                }
            }
        }
    }
}
