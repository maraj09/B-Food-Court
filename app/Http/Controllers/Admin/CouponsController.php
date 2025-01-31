<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CouponEmail;
use App\Models\Coupon;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();

        return view('pages.coupons.admin.coupons', compact('coupons'));
    }

    public function couponLogsIndex()
    {
        $orders = Order::where('status', 'paid')->whereNot('coupon_id', NULL)->orderBy('created_at', 'desc')->get();

        return view('pages.coupons.admin.coupon-logs', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.coupons.admin.createCoupons');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:coupons',
            'discount' => 'required|integer',
            'minimum_amount' => 'required|integer',
            'discount_type' => 'required|in:percentage,fixed',
            'maximum_amount' => 'nullable|integer', // New field validation
            'limit' => 'nullable|integer',
            'limit_type' => 'required|in:global,per_user,on_order',
            'status' => 'nullable|boolean',
            'category_id.*' => 'nullable|exists:item_categories,id',
            'user_id.*' => 'nullable|exists:users,id',
            'approved' => 'nullable|boolean',
            'share_discount' => 'nullable|boolean',
            'item_id.*' => [
                'nullable',
                'exists:items,id',
                function ($attribute, $value, $fail) use ($request) {
                    $item = \App\Models\Item::find($value);
                    if (!$item) {
                        return;
                    }

                    $discount = $request->input('discount');
                    if ($item->price <= $discount) {
                        $fail("The item {$item->item_name} has a price lower than the discount.");
                    }

                    if ($request->has('category_id')) {
                        $categoryIds = $request->input('category_id');
                        if (!in_array($item->category_id, $categoryIds)) {
                            $fail("The item {$item->item_name} does not belong to the selected categories.");
                        }
                    }
                }
            ],
            'expire_date' => 'nullable|date_format:Y-m-d H:i',
        ]);

        $data['status'] = $request->has('status'); // Convert status to boolean
        $data['approved'] = $request->has('approved'); // Convert status to boolean
        $data['share_discount'] = $request->has('share_discount'); // Convert status to boolean

        // Set default values for limit and maximum_amount if null
        $data['limit'] = $data['limit'] ?? 0;
        $data['maximum_amount'] = $data['maximum_amount'] ?? null;

        // Create the coupon instance
        $coupon = Coupon::create($data);

        // Attach categories to the coupon if category_id array is present
        if (isset($data['category_id'])) {
            $coupon->itemCategories()->sync($data['category_id']);
        }

        if (isset($data['item_id'])) {
            $coupon->items()->sync($data['item_id']);
        }

        // Attach users to the coupon if user_id array is present
        if (isset($data['user_id'])) {
            $coupon->users()->sync($data['user_id']);
        }

        if (isset($data['user_id'])) {
            $users = User::whereIn('id', $data['user_id'])->get();

            // Send email to each user
            foreach ($users as $user) {
                try {
                    if ($user->email) {
                        Mail::to($user->email)->send(new CouponEmail($user->id, $coupon->id));
                    }
                    if ($user->onesignal_subs_id) {
                        $userId = $user->onesignal_subs_id;
                        $params = [];
                        $params['include_player_ids'] = [$userId];
                        $message = "Use coupon " . $coupon->code . " on your next order to get " .
                            ($coupon->discount_type == 'fixed' ? '₹' . $coupon->discount : $coupon->discount . '%') .
                            ' discount';
                        $contents = [
                            "en" => $message,
                        ];
                        $params['contents'] = $contents;
                        OneSignalFacade::sendNotificationCustom(
                            $params
                        );
                    }
                } catch (\Exception $e) {
                    // Log the exception or perform any other error handling
                    Log::error('Failed to send email: ' . $e->getMessage());
                }
            }
        }

        return redirect('/dashboard/coupons')->with('success', 'Coupon created successfully.');
    }

    public function toggleStatus(Request $request)
    {
        // Validate the request
        $request->validate([
            'coupon_id' => 'required|exists:coupons,id',
            'status' => 'required|boolean',
        ]);

        // Get the coupon
        $coupon = Coupon::findOrFail($request->coupon_id);

        // Update the status
        $coupon->status = $request->status;
        $coupon->save();

        return response()->json(['success' => true]);
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
    public function edit(Coupon $coupon)
    {

        return view('pages.coupons.admin.editCoupons', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $previousApproved = $coupon->approved;
        $data = $request->validate([
            'code' => [
                'required',
                Rule::unique('coupons')->ignore($coupon->id),
            ],
            'discount' => 'required|integer',
            'minimum_amount' => 'required|integer',
            'discount_type' => 'required|in:percentage,fixed',
            'maximum_amount' => 'nullable|integer',
            'limit' => 'nullable|integer',
            'limit_type' => 'required|in:global,per_user,on_order',
            'status' => 'nullable|boolean',
            'category_id.*' => 'nullable|exists:item_categories,id',
            'user_id.*' => 'nullable|exists:users,id',
            'approved' => 'nullable|boolean',
            'share_discount' => 'nullable|boolean',
            'item_id.*' => [
                'nullable',
                'exists:items,id',
                function ($attribute, $value, $fail) use ($request) {
                    $item = \App\Models\Item::find($value);
                    if (!$item) {
                        return;
                    }

                    $discount = $request->input('discount');
                    if (round($item->price) <= $discount) {
                        $fail("The item {$item->item_name} has a price lower than the discount.");
                    }

                    if ($request->has('category_id')) {
                        $categoryIds = $request->input('category_id');
                        if (!in_array($item->category_id, $categoryIds)) {
                            $fail("The item {$item->item_name} does not belong to the selected categories.");
                        }
                    }
                }
            ],
            'expire_date' => 'nullable|date_format:Y-m-d H:i',
        ]);

        // Adjust status based on checkbox value
        $data['status'] = (bool) $request->status;
        $data['approved'] = (bool) $request->approved;
        $data['share_discount'] = (bool) $request->share_discount;

        // Set default values for limit and maximum_amount if null
        $data['limit'] = $data['limit'] ?? 0;
        $data['maximum_amount'] = $data['maximum_amount'] ?? null;

        $previousUserIds = $coupon->users()->pluck('id');
        // Update the coupon instance with validated data
        $coupon->update($data);

        // Sync categories to the coupon if category_id array is present
        if ($request->has('category_id')) {
            $coupon->itemCategories()->sync($request->category_id);
        } else {
            // If no categories are provided, detach all existing categories
            $coupon->itemCategories()->detach();
        }

        // Sync users to the coupon if user_id array is present
        if ($request->has('user_id')) {
            $coupon->users()->sync($request->user_id);
        } else {
            // If no users are provided, detach all existing users
            $coupon->users()->detach();
        }

        // Sync categories to the coupon if category_id array is present
        if ($request->has('item_id')) {
            $coupon->items()->sync($request->item_id);
        } else {
            // If no categories are provided, detach all existing categories
            $coupon->items()->detach();
        }

        if ($previousApproved) {
            // Send email only to new users
            $newUserIds = collect($request->user_id)->diff($previousUserIds)->toArray();
        } else {
            // Send email to all users associated with the coupon
            $newUserIds = $coupon->users()->pluck('id')->toArray();
        }

        // Get the newly added users
        $newUsers = User::whereIn('id', $newUserIds)->get();

        // Send email to each newly added user
        foreach ($newUsers as $user) {
            try {
                if ($user->email) {
                    Mail::to($user->email)->send(new CouponEmail($user->id, $coupon->id));
                }
                if ($user->onesignal_subs_id) {
                    $userId = $user->onesignal_subs_id;
                    $params = [];
                    $params['include_player_ids'] = [$userId];
                    $message = "Use coupon " . $coupon->code . " on your next order to get " .
                        ($coupon->discount_type == 'fixed' ? '₹' . $coupon->discount : $coupon->discount . '%') .
                        ' discount';
                    $contents = [
                        "en" => $message,
                    ];
                    $params['contents'] = $contents;
                    OneSignalFacade::sendNotificationCustom(
                        $params
                    );
                }
            } catch (\Exception $e) {
                // Log the exception or perform any other error handling
                Log::error('Failed to send email: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.coupons.edit', $coupon)->with('success', 'Coupon updated successfully.');
    }

    public function getItemsByCategory(Request $request)
    {
        $categoryIds = $request->category_ids;

        if (empty($categoryIds)) {
            $items = Item::where('approve', 1)
                ->where('status', 1)->with('vendor')
                ->get();
            return response()->json($items);
        }

        $items = Item::whereIn('category_id', $categoryIds)
            ->where('approve', 1)
            ->where('status', 1)->with('vendor')
            ->get();


        return response()->json($items);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect('/dashboard/coupons')->with('success', 'Coupon deleted successfully.');
    }
}
