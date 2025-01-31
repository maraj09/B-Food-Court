<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = User::role('vendor')->get();
        return view('pages.vendors.admin.vendor', compact('vendors'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand_name' => 'required|string|max:255|unique:vendors,brand_name',
            'commission' => 'required|numeric|min:0|max:100',
            'phone' => 'required|phone:BD,IN|unique:users,phone',
            'fassi_no' => 'nullable|string|max:255',
            'stall_no' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'documents.*' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,tiff,pdf|max:10485',
            'details' => 'nullable|string',
        ], [
            'phone.phone' => 'The :attribute field must be a valid number.',
        ]);

        // Store the avatar
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = $avatar->storeAs('images/avatars', $avatarName, 'public');
            $avatarPath = 'storage/' . $avatarPath;
        }

        // Create a new user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make(Str::random(8)),
        ])->assignRole('vendor');

        // Create a new vendor for the user
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $documentName = uniqid() . '.' . $document->getClientOriginalExtension();
                $documentPath = $document->storeAs('vendor_documents', $documentName, 'public');
                $documentPath = 'storage/' . $documentPath;
                $documents[] = [
                    'filename' => $document->getClientOriginalName(),
                    'filepath' => $documentPath,
                ];
            }
        }
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'avatar' =>  $avatarPath,
            'brand_name' => $request->input('brand_name'),
            'commission' => $request->input('commission'),
            'fassi_no' => $request->input('fassi_no'),
            'stall_no' => $request->input('stall_no'),
            'documents' => $documents,
            'details' => $request->details,
        ]);

        VendorBank::create([
            'vendor_id' => $vendor->id, // Example vendor ID
            'balance' => 0,
            'amount_paid' => 0,
        ]);

        if ($user->email) {
            Mail::to($user->email)->send(new WelcomeEmail($user->id));
        }

        return response()->json(['success' => true, 'message' => 'Vendor created successfully']);
    }

    public function show(User $user)
    {
        $vendorId = $user->vendor->id;
        $orders = OrderItem::whereHas('item.vendor', function ($query) use ($vendorId) {
            $query->where('id', $vendorId);
        })->where('status', '!=', 'unpaid')->orderBy('created_at', 'desc')->get()->groupBy('order_id');
        return view('pages.vendors.admin.show', compact(['user', 'orders']));
    }

    public function edit(User $user)
    {
        $vendorId = $user->vendor->id;
        $orders = OrderItem::whereHas('item.vendor', function ($query) use ($vendorId) {
            $query->where('id', $vendorId);
        })->where('status', '!=', 'unpaid')->orderBy('created_at', 'desc')->get()->groupBy('order_id');
        return view('pages.vendors.admin.show', compact(['user', 'orders']));
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand_name' => 'required|string|max:255|unique:vendors,brand_name,' . $user->vendor->id . ',id',
            'commission' => 'required|numeric|min:0|max:100',
            'phone' => 'required|phone:BD,IN|unique:users,phone,' . $user->id . ',id',
            'fassi_no' => 'nullable|string|max:255',
            'stall_no' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id . ',id',
            'documents.*' => 'nullable|mimes:jpeg,jpg,png,gif,bmp,tiff,pdf|max:10485',
            'details' => 'nullable|string',
        ], [
            'phone.phone' => 'The :attribute field must be a valid number.',
        ]);

        $vendor = $user->vendor;

        // Update vendor data
        $vendor->brand_name = $request->input('brand_name');
        $vendor->commission = $request->input('commission');
        $vendor->fassi_no = $request->input('fassi_no');
        $vendor->stall_no = $request->input('stall_no');

        // Update user data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        // Handle avatar
        if ($request->hasFile('avatar')) {
            if ($user->vendor->avatar) {
                File::delete($user->vendor->avatar);
            }
            $avatar = $request->file('avatar');
            $avatarName = uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = $avatar->storeAs('images/avatars', $avatarName, 'public');
            $avatarPath = 'storage/' . $avatarPath;
            $vendor->avatar = $avatarPath;
        }

        $documents = $vendor->documents;
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $documentName = uniqid() . '.' . $document->getClientOriginalExtension();
                $documentPath = $document->storeAs('vendor_documents', $documentName, 'public');
                $documentPath = 'storage/' . $documentPath;
                $documents[] = [
                    'filename' => $document->getClientOriginalName(),
                    'filepath' => $documentPath,
                ];
            }
        }
        $vendor->documents = $documents;
        $vendor->details = $request->input('details');

        $vendor->save();
        $user->save();

        return redirect('/dashboard/vendors/' . $user->id . '/edit')->with('success', 'Vendor updated successfully');
    }

    public function destroy(User $user)
    {

        if ($user->vendor->avatar) {
            File::delete($user->vendor->avatar);
        }

        $user->delete();

        return redirect('/dashboard/vendors')->with('success', 'Vendor deleted successfully.');
    }

    public function updateApprove($id)
    {
        try {
            // Find the vendor by ID
            $vendor = Vendor::where('id', $id)->first();

            // Toggle the status (assuming 'status' is a boolean field in your vendors table)
            $vendor->approve = !$vendor->approve;

            // Save the changes
            $vendor->save();

            // Return a success response (you can customize this based on your needs)
            return response()->json(['status' => $vendor->approve, 'message' => 'Vendor approved successfully']);
        } catch (\Exception $e) {
            // Return an error response if an exception occurs
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeDocument(Request $request, $vendorId)
    {
        try {
            $vendor = Vendor::findOrFail($vendorId);

            // Filter out the document to be removed based on the provided file path
            $newDocuments = array_values(array_filter($vendor->documents, function ($doc) use ($request) {
                return $doc['filepath'] !== $request->input('filepath');
            }));

            // Update the 'documents' array of the vendor model
            $vendor->documents = $newDocuments;

            // Save the updated 'documents' array back to the vendor model
            $vendor->save();
            File::delete($request->input('filepath'));
            return response()->json(['message' => 'Document removed successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred while removing document.'], 500);
        }
    }
}
