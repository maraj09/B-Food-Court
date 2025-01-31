<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::where('vendor_id', auth()->user()->vendor->id)->orderBy('created_at', 'desc')->get();

        return view('pages.coupons.vendor.coupons', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.coupons.vendor.createCoupons');
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
            'share_discount' => 'nullable|boolean',
            'item_id.*' => [
                'nullable',
                'exists:items,id',
                function ($attribute, $value, $fail) use ($request) {
                    $item = \App\Models\Item::find($value);
                    if (!$item) {
                        return;
                    }

                    if ($item->vendor_id != auth()->user()->vendor->id) {
                        $fail("The item {$item->item_name} not belongs to you.");
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
        $data['share_discount'] = $request->has('share_discount'); // Convert status to boolean

        // Set default values for limit and maximum_amount if null
        $data['limit'] = $data['limit'] ?? 0;
        $data['vendor_id'] = auth()->user()->vendor->id;
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

        return redirect('/vendor/coupons')->with('success', 'Coupon created successfully.');
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
        return view('pages.coupons.vendor.editCoupons', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
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
            'share_discount' => 'nullable|boolean',
            'item_id.*' => [
                'nullable',
                'exists:items,id',
                function ($attribute, $value, $fail) use ($request) {
                    $item = \App\Models\Item::find($value);
                    if (!$item) {
                        return;
                    }

                    if ($item->vendor_id != auth()->user()->vendor->id) {
                        $fail("The item {$item->item_name} not belongs to you.");
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

        return redirect()->route('vendor.coupons.edit', $coupon)->with('success', 'Coupon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect('/vendor/coupons')->with('success', 'Coupon deleted successfully.');
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
            ->where('status', 1)->where('vendor_id', auth()->user()->vendor->id)->with('vendor')
            ->get();


        return response()->json($items);
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
}
