<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\User;
use App\Models\VendorBank;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PayoutsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendorId = Auth::user()->vendor->id;
        $vendorBank = VendorBank::where('vendor_id', $vendorId)->first();
        $payouts = Payout::where('vendor_id', $vendorId)->orderByDesc('created_at')->get();
        $pendingAmount = Payout::where('vendor_id', $vendorId)->where('status', '!=', 'transferred')->sum('request_amount');
        return view('pages.payout.vendor.payouts', compact(['vendorBank', 'payouts', 'pendingAmount']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function search(Request $request)
    {
        $vendorId = Auth::user()->vendor->id;
        $searchTerm = $request->input('search_term');

        // Perform search based on transaction ID or remarks
        $payouts = Payout::where('vendor_id', $vendorId)->where('transaction_id', 'like', "%$searchTerm%")
            ->orWhere('remark', 'like', "%$searchTerm%")
            ->get();

        // Return the filtered payouts as JSON data
        $htmlContent = view('pages.payout.vendor.partials.payouts_table', ['payouts' => $payouts])->render();
        return response()->json(['htmlContent' => $htmlContent]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'request_amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    // Get the vendor ID of the authenticated user
                    $vendorId = Auth::user()->vendor->id;

                    // Retrieve the VendorBank record
                    $vendorBank = VendorBank::where('vendor_id', $vendorId)->first();

                    // Check if the request_amount is greater than the VendorBank balance
                    if ($value > $vendorBank->balance) {
                        $fail('The request amount cannot exceed the vendor bank balance.');
                    }
                },
            ],
            'remark' => 'nullable|string|max:255',
            'payment_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Assuming maximum file size is 2MB
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return redirect()->back()->with('showDrawer', true)->withErrors($validator)->withInput();
        }

        $payout = new Payout();
        $vendorId = Auth::user()->vendor->id;
        // Save payout data to the database

        $vendorBank = VendorBank::where('vendor_id', $vendorId)->first();
        $vendorBank->balance -=  $request->request_amount;
        $vendorBank->save();

        $payout->request_amount = $request->request_amount;
        $payout->remark = $request->remark;
        $payout->vendor_id = $vendorId;

        // Save payment image if provided
        if ($request->hasFile('payment_image')) {
            $image = $request->file('payment_image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/payments', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;
            $payout->payment_image = $imagePath;
        }
        // Perform any additional processing if needed

        $payout->save();

        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $url = '/dashboard/payouts';
            $message = 'A new payout request by ' . Auth::user()->vendor->brand_name;
            if ($admin->onesignal_subs_id) {
                $userId = $admin->onesignal_subs_id;
                $params = [];
                $params['include_player_ids'] = [$userId];
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

        // Redirect back with success message or do whatever you need
        return redirect()->back()->with('success', 'Payout request submitted successfully!');
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
