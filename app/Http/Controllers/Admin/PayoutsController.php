<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Vendor;
use App\Models\VendorBank;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PayoutsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payouts = Payout::orderByDesc('created_at')->get();
        $vendors = Vendor::all();
        return view('pages.payout.admin.payouts', compact(['payouts', 'vendors']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendorId' => 'required',
            'request_amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $vendorId = $request->vendorId;
                    $vendorBank = VendorBank::where('vendor_id', $vendorId)->first();
                    if ($request->payout_mode === 'create') {
                        if ($vendorBank->balance) {
                            if ($value > $vendorBank->balance) {
                                $fail('The request amount cannot exceed the vendor bank balance.');
                            }
                        } else {
                            $fail('The request amount cannot exceed the vendor bank balance.');
                        }
                    } elseif ($request->payout_mode === 'edit') {
                        // Check if the new request amount exceeds the vendor bank balance
                        $payout = Payout::findOrFail($request->payout_id);
                        $previousPayoutAmount = $payout->request_amount;
                        $balanceAfterEdit = $vendorBank->balance + $previousPayoutAmount - $value;
                        if ($balanceAfterEdit < 0) {
                            $fail('The request amount cannot exceed the vendor bank balance.');
                        }
                    }
                },
            ],
            'remark' => 'nullable|string|max:255',
            'payment_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable',
            'date' => 'nullable|date',
            'transaction_id' => 'nullable',
            'payment_mode' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('showDrawer', true)->withErrors($validator)->withInput();
        }

        $vendorId = $request->vendorId;
        $vendorBank = VendorBank::where('vendor_id', $vendorId)->first();

        if ($request->payout_mode === 'create') {
            // Create a new payout
            $payout = new Payout();
            $vendorBank->balance -=  $request->request_amount;
            if ($request->status == 'transferred') {
                $vendorBank->amount_paid += $request->request_amount;
            }
            $vendorBank->save();
        } elseif ($request->payout_mode === 'edit') {
            // Find the existing payout by ID
            $payout = Payout::findOrFail($request->payout_id);
            if ($payout->vendor_id == $vendorId) {
                // here need to add previous amount reason for admin can update amount also !!
                $vendorBank->balance +=  $payout->request_amount;
                $vendorBank->balance -=  $request->request_amount;
                if ($payout->status == 'transferred') {
                    $vendorBank->amount_paid -= $request->request_amount;
                }
                if ($request->status == 'transferred') {
                    $vendorBank->amount_paid += $request->request_amount;
                }
                $vendorBank->save();
            } else {
                // here need to add previous amount reason for admin can update vendor also !!
                $vendorBankTemp = VendorBank::where('vendor_id', $payout->vendor_id)->first();
                $vendorBankTemp->balance +=  $payout->request_amount;
                if ($payout->status == 'transferred') {
                    $vendorBankTemp->amount_paid -= $payout->request_amount;
                }
                $vendorBankTemp->save();
                $vendorBank->balance -=  $request->request_amount;
                if ($request->status == 'transferred') {
                    $vendorBank->amount_paid += $request->request_amount;
                }
                $vendorBank->save();
            }

            $vendor = Vendor::where('id', $vendorId)->first();

            if ($vendor->user->onesignal_subs_id) {
                $url = '/vendor/payouts';
                $message = 'You Payout request is updated!';
                $userId = $vendor->user->onesignal_subs_id;
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
        } else {
            // Invalid payout mode
            return redirect()->back()->withErrors(['message' => 'Invalid payout mode']);
        }

        // Save payout data to the database
        $payout->request_amount = $request->request_amount;
        $payout->remark = $request->remark;
        $payout->status = $request->status ?? 'pending';
        $payout->date = $request->date ?? now();
        $payout->vendor_id = $vendorId;
        $payout->transaction_id = $request->transaction_id;
        $payout->payment_mode = $request->payment_mode;


        // Save payment image if provided
        if ($request->hasFile('payment_image')) {
            if ($payout->payment_image) {
                File::delete($payout->payment_image);
            }
            $image = $request->file('payment_image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/payments', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;
            $payout->payment_image = $imagePath;
        }

        if ($request->payment_image_remove == 1) {
            if ($payout->payment_image) {
                File::delete($payout->payment_image);
            }
            $payout->payment_image = null;
        }
        // Perform any additional processing if needed

        $payout->save();

        // Redirect back with success message or do whatever you need
        return redirect()->back()->with('success', 'Payout request submitted successfully!');
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search_term');

        // Perform search based on vendor name
        $payouts = Payout::whereHas('vendor', function ($query) use ($searchTerm) {
            $query->where('brand_name', 'like', "%$searchTerm%");
        })->orWhere('transaction_id', 'like', "%$searchTerm%")
            ->orWhere('remark', 'like', "%$searchTerm%")->orderByDesc('created_at')
            ->get();

        // Return the filtered payouts as JSON data
        $htmlContent = view('pages.payout.admin.partials.payouts_table', ['payouts' => $payouts])->render();
        return response()->json(['htmlContent' => $htmlContent]);
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

    public function getBank(Request $request)
    {
        // Retrieve the vendor ID from the request
        $vendorId = $request->input('vendor_id');

        // Retrieve the bank information for the selected vendor
        $bankInfo = VendorBank::where('vendor_id', $vendorId)->first();

        $pendingAmount = Payout::where('vendor_id', $vendorId)->where('status', '!=', 'transferred')->sum('request_amount');

        // Return the bank information as a response
        return response()->json(['bankInfo' =>  $bankInfo, 'pendingAmount' => $pendingAmount]);
    }
}
