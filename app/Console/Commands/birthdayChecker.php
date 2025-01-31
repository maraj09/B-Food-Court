<?php

namespace App\Console\Commands;

use App\Mail\PointsCreditEmail;
use App\Models\Customer;
use App\Models\CustomerPointLog;
use App\Models\Notification;
use App\Models\Point;
use Berkayk\OneSignal\OneSignalFacade;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class birthdayChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:birthday-checker';

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
        // Get today's date
        $point_data = Point::first();
        $birthdayPointStatus = $point_data->birthday_points['status'];
        $points = $point_data->birthday_points['points'] ?? 0;

        if ($birthdayPointStatus == 'active') {
            $customers = Customer::whereMonth('date_of_birth', '=', Carbon::today()->month)
                ->whereDay('date_of_birth', '=', Carbon::today()->day)
                ->get();

            foreach ($customers as $customer) {
                $customer->user->point->points += $points;
                $customer->user->point->save();
                if ($customer->user->onesignal_subs_id) {
                    $userId = $customer->user->onesignal_subs_id;
                    $params = [];
                    $params['include_player_ids'] = [$userId];
                    $message = "Happy Birthday to you!";
                    $contents = [
                        "en" => $message,
                    ];
                    $params['contents'] = $contents;
                    OneSignalFacade::sendNotificationCustom(
                        $params
                    );
                }
                if ($points > 0) {
                    CustomerPointLog::create([
                        'user_id' => $customer->user_id,
                        'action' => 'Birthday',
                        'points' => $points,
                        'details' => 'Points added for birthday'
                    ]);
                    Notification::create([
                        'user_id' => $customer->user_id,
                        'message' => $point_data->birthday_points['alert_message'],
                    ]);
                    try {
                        if ($customer->user->email) {
                            Mail::to($customer->user->email)->send(new PointsCreditEmail($customer->user_id, $point_data->birthday_points['points'], $point_data->birthday_points['alert_message']));
                        }
                    } catch (\Exception $e) {
                        // Log the exception or perform any other error handling
                        Log::error('Failed to send email: ' . $e->getMessage());
                    }
                }
            }
        }
    }
}
