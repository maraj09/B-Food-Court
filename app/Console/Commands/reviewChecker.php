<?php

namespace App\Console\Commands;

use App\Models\OrderItem;
use App\Models\User;
use Berkayk\OneSignal\OneSignalFacade;
use Carbon\Carbon;
use Illuminate\Console\Command;

class reviewChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:review-checker';

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
        $endThreshold = Carbon::now()->subMinutes(10);
        $startThreshold = $endThreshold->copy()->subMinute();

        // Query order items that are delivered and updated within the range
        $orderItems = OrderItem::where('status', 'delivered')
            ->whereBetween('updated_at', [$startThreshold, $endThreshold])
            ->get();

        foreach ($orderItems as $orderItem) {
            $userRating = $orderItem->item->ratings
                ->where('order_id', $orderItem->order->id)
                ->where('user_id', $orderItem->order->user_id)
                ->first();

            if (is_null($userRating?->rating)) {
                $user = User::where('id', $orderItem->order->user_id)->first();
                if ($user->onesignal_subs_id) {
                    $userId = $user->onesignal_subs_id;
                    $params = [];
                    $params['include_player_ids'] = [$userId];
                    $params['url'] = '/orders/' . $orderItem->order_id;
                    $message = "Please give a quick review!";
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
    }
}
