<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PromotionEmail;
use App\Models\Promotion;
use App\Models\User;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $acceptedOneSignalUsers = User::whereNot('onesignal_subs_id', null)->get();
        return view('pages.promotions.promotion', compact('acceptedOneSignalUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function sendPushNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        if ($user) {
            if ($user->onesignal_subs_id) {

                if ($request->hasFile('push_banner')) {
                    $bannerPath = $request->file('push_banner')->store('public/temp');
                    $pushBannerUrl = Storage::url($bannerPath);
                }

                if ($request->hasFile('push_avatar')) {
                    $avatarPath = $request->file('push_avatar')->store('public/temp');
                    $pushAvatarUrl = Storage::url($avatarPath);
                }

                $title = $request->push_title;
                $message = $request->push_description;
                $url = $request->push_link;
                $userId = $user->onesignal_subs_id;

                $params = [];

                $params['include_player_ids'] = [$userId];
                $params['url'] = $url;
                if (isset($pushBannerUrl)) {
                    $params['chrome_web_image'] = asset($pushBannerUrl);
                }
                if (isset($pushAvatarUrl)) {
                    $params['chrome_web_icon'] = asset($pushAvatarUrl);
                }
                $contents = [
                    "en" => $message,
                ];
                $headings = [
                    "en" => $title,
                ];
                $params['contents'] = $contents;
                $params['headings'] = $headings;

                OneSignalFacade::sendNotificationCustom(
                    $params
                );

                return response()->json(['success' => true, 'message' => 'Push notification send successfully']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Push notification sending failed']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function calculateUsers(Request $request)
    {
        // Retrieve the filter inputs
        $filter = $request->input('filter');
        $comparison = $request->input('comparison', 'is_more_then');
        $value = $request->input('value', 0);
        $userType = $request->input('user_type'); // 'all', 'customer', or 'vendor'

        $comparisonOperator = $comparison === 'is_less_then' ? '<' : '>';

        $totalUser = 0;

        // Define a function to count users based on role and orders
        $countUsers = function ($role, $usersWithOrdersQuery) {
            return User::role($role)
                ->whereIn('id', $usersWithOrdersQuery)
                ->count();
        };

        // Apply the filter based on the selected option
        if ($filter === 'total_orders') {

            // Handle customers
            if ($userType === 'all' || $userType === 'customer') {
                $usersWithOrdersQuery = DB::table('orders')
                    ->select('user_id')
                    ->where('status', 'paid')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(*) ' . $comparisonOperator . ' ?', [$value])
                    ->pluck('user_id');

                $totalUser += $countUsers('customer', $usersWithOrdersQuery);
            }

            // Handle vendors
            if ($userType === 'all' || $userType === 'vendor') {
                $vendorsWithOrdersQuery = DB::table('vendors')
                    ->join('items', 'vendors.id', '=', 'items.vendor_id')
                    ->join('order_items', 'items.id', '=', 'order_items.item_id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->select('vendors.user_id')
                    ->where('orders.status', 'paid')
                    ->groupBy('vendors.id', 'orders.id')
                    ->havingRaw('COUNT(DISTINCT orders.id) ' . $comparisonOperator . ' ?', [$value])
                    ->pluck('vendors.user_id');

                $totalUser += $countUsers('vendor', $vendorsWithOrdersQuery);
            }
        } elseif ($filter === 'total_orders_value') {

            // Handle customers' total order amount where status is 'paid'
            if ($userType === 'all' || $userType === 'customer') {
                $customersWithOrderValueQuery = DB::table('orders')
                    ->select('user_id')
                    ->where('status', 'paid')
                    ->groupBy('user_id')
                    ->havingRaw('SUM(order_amount) ' . $comparisonOperator . ' ?', [$value])
                    ->pluck('user_id');

                $totalUser += $countUsers('customer', $customersWithOrderValueQuery);
            }

            // Handle vendors' total item prices where status is 'paid'
            if ($userType === 'all' || $userType === 'vendor') {
                $vendorsWithOrderValueQuery = DB::table('vendors')
                    ->join('items', 'vendors.id', '=', 'items.vendor_id')
                    ->join('order_items', 'items.id', '=', 'order_items.item_id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->select('vendors.user_id')
                    ->where('orders.status', 'paid')
                    ->groupBy('vendors.user_id')
                    ->havingRaw('SUM(order_items.price * order_items.quantity) ' . $comparisonOperator . ' ?', [$value])
                    ->pluck('vendors.user_id');

                $totalUser += $countUsers('vendor', $vendorsWithOrderValueQuery);
            }
        } elseif ($filter === 'total_points_earn') {
            if ($userType === 'all' || $userType === 'customer') {
                $customersWithPointsQuery = DB::table('customer_point_logs')
                    ->select('user_id')
                    ->whereNotIn('action', ['Penalty', 'Redeem'])
                    ->groupBy('user_id')
                    ->havingRaw('SUM(points) ' . $comparisonOperator . ' ?', [$value])
                    ->pluck('user_id');

                $totalUser += $countUsers('customer', $customersWithPointsQuery);
            }

            // Vendors do not earn points, so their count is always 0 for this filter
            if ($userType === 'vendor') {
                $totalUser = 0;
            }
        } elseif ($filter === 'total_points_redeem') {
            if ($userType === 'all' || $userType === 'customer') {
                $customersWithPointsQuery = DB::table('customer_point_logs')
                    ->select('user_id')
                    ->where('action', 'Redeem')
                    ->groupBy('user_id')
                    ->havingRaw('SUM(points) ' . $comparisonOperator . ' ?', [$value])
                    ->pluck('user_id');

                $totalUser += $countUsers('customer', $customersWithPointsQuery);
            }

            // Vendors do not earn points, so their count is always 0 for this filter
            if ($userType === 'vendor') {
                $totalUser = 0;
            }
        } elseif ($filter === 'total_review') {
            if ($userType === 'all' || $userType === 'customer') {
                $customersWithReviewsQuery = DB::table('ratings')
                    ->select('user_id')
                    ->whereNotNull('user_id')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(*) ' . $comparisonOperator . ' ?', [$value])
                    ->pluck('user_id');

                $totalUser += $countUsers('customer', $customersWithReviewsQuery);
            }

            if ($userType === 'all' || $userType === 'vendor') {
                $vendorsWithReviewsQuery = DB::table('ratings')
                    ->join('items', 'ratings.item_id', '=', 'items.id')
                    ->join('vendors', 'items.vendor_id', '=', 'vendors.id')
                    ->select('vendors.user_id')
                    ->whereNotNull('vendors.user_id')
                    ->groupBy('vendors.user_id')
                    ->havingRaw('COUNT(*) ' . $comparisonOperator . ' ?', [$value])
                    ->pluck('vendors.user_id');

                $totalUser += $countUsers('vendor', $vendorsWithReviewsQuery);
            }
        } else {
            // Handle other filters or return an error for unknown filters
            return response()->json(['error' => 'Invalid filter'], 400);
        }

        // Return the count of users
        return response()->json(['userCount' => $totalUser]);
    }


    /**
     * Display the specified resource.
     */
    public function store(Request $request)
    {
        Storage::disk('public')->deleteDirectory('temp');

        $request->merge([
            'email_status' => $request->has('email_status') ? true : false,
            'push_status' => $request->has('push_status') ? true : false,
        ]);

        $validated = $request->validate([
            'promotion_title' => 'required|string|max:255',
            'promotion_date_time' => 'required|date',
            'email_status' => 'boolean',
            'push_status' => 'boolean',
            'email_title' => 'nullable|string|max:255',
            'email_description' => 'nullable|string',
            'push_title' => 'nullable|string|max:255',
            'push_description' => 'nullable|string',
            'push_link' => 'nullable|string|max:255',
            'push_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'push_avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_type' => 'required|string|in:all,customer,vendor',
            'filter.*' => 'required|string',
            'comparison.*' => 'required|string|in:is_less_then,is_more_then',
            'value.*' => 'required|numeric',
            'operator.*' => 'required|string|in:and,or', // default to 'and'
        ]);

        $pushBannerPath = $request->hasFile('push_banner') ? $request->file('push_banner')->store('public/promotions/banners') : null;
        $pushAvatarPath = $request->hasFile('push_avatar') ? $request->file('push_avatar')->store('public/promotions/avatars') : null;

        $userType = $validated['user_type'];
        $filters = $validated['filter'];
        $comparisons = $validated['comparison'];
        $values = $validated['value'];
        $operators = $validated['operator'];

        $promotionUsers = collect();

        foreach ($filters as $index => $filter) {
            $comparisonOperator = $comparisons[$index] === 'is_less_then' ? '<=' : '>=';
            $value = $values[$index];

            if ($filter === 'total_orders') {

                if ($userType === 'all' || $userType === 'customer') {
                    $usersWithOrdersQuery = DB::table('orders')
                        ->select('user_id')
                        ->where('status', 'paid')
                        ->groupBy('user_id')
                        ->havingRaw('COUNT(*) ' . $comparisonOperator . ' ?', [$value])
                        ->pluck('user_id');

                    $promotionUsers = $promotionUsers->merge($usersWithOrdersQuery);
                }

                if ($userType === 'all' || $userType === 'vendor') {
                    $vendorsWithOrdersQuery = DB::table('vendors')
                        ->join('items', 'vendors.id', '=', 'items.vendor_id')
                        ->join('order_items', 'items.id', '=', 'order_items.item_id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->select('vendors.user_id')
                        ->where('orders.status', 'paid')
                        ->groupBy('vendors.user_id')
                        ->havingRaw('COUNT(DISTINCT orders.id) ' . $comparisonOperator . ' ?', [$value])
                        ->pluck('vendors.user_id');

                    $promotionUsers = $promotionUsers->merge($vendorsWithOrdersQuery);
                }
            } elseif ($filter === 'total_orders_value') {

                if ($userType === 'all' || $userType === 'customer') {
                    $customersWithOrderValueQuery = DB::table('orders')
                        ->select('user_id')
                        ->where('status', 'paid')
                        ->groupBy('user_id')
                        ->havingRaw('SUM(order_amount) ' . $comparisonOperator . ' ?', [$value])
                        ->pluck('user_id');

                    $promotionUsers = $promotionUsers->merge($customersWithOrderValueQuery);
                }

                if ($userType === 'all' || $userType === 'vendor') {
                    $vendorsWithOrderValueQuery = DB::table('vendors')
                        ->join('items', 'vendors.id', '=', 'items.vendor_id')
                        ->join('order_items', 'items.id', '=', 'order_items.item_id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->select('vendors.user_id')
                        ->where('orders.status', 'paid')
                        ->groupBy('vendors.user_id')
                        ->havingRaw('SUM(order_items.price * order_items.quantity) ' . $comparisonOperator . ' ?', [$value])
                        ->pluck('vendors.user_id');

                    $promotionUsers = $promotionUsers->merge($vendorsWithOrderValueQuery);
                }
            } elseif ($filter === 'total_points_earn') {

                if ($userType === 'all' || $userType === 'customer') {
                    $customersWithPointsQuery = DB::table('customer_point_logs')
                        ->select('user_id')
                        ->whereNotIn('action', ['Penalty', 'Redeem'])
                        ->groupBy('user_id')
                        ->havingRaw('SUM(points) ' . $comparisonOperator . ' ?', [$value])
                        ->pluck('user_id');

                    $promotionUsers = $promotionUsers->merge($customersWithPointsQuery);
                }

                if ($userType === 'vendor') {
                    $promotionUsers = $promotionUsers->merge([]);
                }
            } elseif ($filter === 'total_points_redeem') {

                if ($userType === 'all' || $userType === 'customer') {
                    $customersWithPointsQuery = DB::table('customer_point_logs')
                        ->select('user_id')
                        ->where('action', 'Redeem')
                        ->groupBy('user_id')
                        ->havingRaw('SUM(points) ' . $comparisonOperator . ' ?', [$value])
                        ->pluck('user_id');

                    $promotionUsers = $promotionUsers->merge($customersWithPointsQuery);
                }

                if ($userType === 'vendor') {
                    $promotionUsers = $promotionUsers->merge([]);
                }
            } elseif ($filter === 'total_review') {

                if ($userType === 'all' || $userType === 'customer') {
                    $customersWithReviewsQuery = DB::table('ratings')
                        ->select('user_id')
                        ->whereNotNull('user_id')
                        ->groupBy('user_id')
                        ->havingRaw('COUNT(*) ' . $comparisonOperator . ' ?', [$value])
                        ->pluck('user_id');

                    $promotionUsers = $promotionUsers->merge($customersWithReviewsQuery);
                }

                if ($userType === 'all' || $userType === 'vendor') {
                    $vendorsWithReviewsQuery = DB::table('ratings')
                        ->join('items', 'ratings.item_id', '=', 'items.id')
                        ->join('vendors', 'items.vendor_id', '=', 'vendors.id')
                        ->select('vendors.user_id')
                        ->whereNotNull('vendors.user_id')
                        ->groupBy('vendors.user_id')
                        ->havingRaw('COUNT(*) ' . $comparisonOperator . ' ?', [$value])
                        ->pluck('vendors.user_id');

                    $promotionUsers = $promotionUsers->merge($vendorsWithReviewsQuery);
                }
            } else {
                return response()->json(['error' => 'Invalid filter'], 400);
            }

            // Apply the logical operator if needed
            if ($operators[$index] === 'or') {
                $promotionUsers = $promotionUsers->unique(); // To ensure unique users across different conditions
            }
        }

        $promotion = Promotion::create([
            'promotion_title' => $validated['promotion_title'],
            'promotion_date_time' => $validated['promotion_date_time'],
            'email_status' => $validated['email_status'] ?? true,
            'push_status' => $validated['push_status'] ?? true,
            'email_title' => $request->email_title,
            'email_description' => $request->email_description,
            'push_title' => $request->push_title,
            'push_description' => $request->push_description,
            'push_link' => $request->push_link,
            'push_banner' => $pushBannerPath,
            'push_avatar' => $pushAvatarPath,
            'user_type' => $validated['user_type'],
        ]);

        // Store the user IDs for the promotion
        foreach ($promotionUsers->unique() as $userId) {
            DB::table('promotion_users')->insert([
                'user_id' => $userId,
                'promotion_id' => $promotion->id, // Assuming you have a promotion ID to associate with
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $user = User::find($userId);

            if ($user->onesignal_subs_id) {
                $title = $request->push_title;
                $message = $request->push_description;
                $url = $request->push_link;
                $userId = $user->onesignal_subs_id;

                $params = [];

                $params['include_player_ids'] = [$userId];
                $params['url'] = $url;
                if (isset($pushBannerPath)) {
                    $params['chrome_web_image'] = asset($pushBannerPath);
                }
                if (isset($pushAvatarPath)) {
                    $params['chrome_web_icon'] = asset($pushAvatarPath);
                }
                $contents = [
                    "en" => $message,
                ];
                $headings = [
                    "en" => $title,
                ];
                $params['contents'] = $contents;
                $params['headings'] = $headings;

                OneSignalFacade::sendNotificationCustom(
                    $params
                );
            }

            if ($user->email) {
                try {
                    Mail::to($user->email)->send(new PromotionEmail($request->email_title, $request->email_description, $user));
                } catch (\Exception $e) {
                    // Log the exception or perform any other error handling
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }
        }

        foreach ($filters as $index => $filter) {
            DB::table('promotion_conditions')->insert([
                'promotion_id' =>  $promotion->id,
                'filter' => $filter,
                'comparison' =>  $comparisons[$index],
                'value' => $values[$index],
                'operator' => $operators[$index],
                'sequence' => $index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Promotion Send Successfully!');
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
}
