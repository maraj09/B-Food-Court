<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use App\Models\Vendor;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $items = Item::where('vendor_id', $user->vendor->id)->get();
        return view('pages.items.vendor.item', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function search(Request $request)
    {
        $vendorId = Auth::user()->vendor->id;
        $searchTerm = $request->input('search_term');

        // Perform search based on transaction ID or remarks
        $items = Item::where('vendor_id', $vendorId)->where('item_name', 'like', "%$searchTerm%")
            ->get();

        // Return the filtered payouts as JSON data
        $htmlContent = view('pages.items.vendor.partials.items_table', ['items' => $items])->render();
        return response()->json(['htmlContent' => $htmlContent]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'category_id' => 'required|exists:item_categories,id',
            'price' => 'required|integer',
            'description' => 'nullable',
            'item_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'boolean',
            'points_status' => 'boolean',
            'item_type' => 'required|string',
        ]);

        $user = Auth::user();

        // Store the image
        $imagePath = null;
        if ($request->hasFile('item_image')) {
            $image = $request->file('item_image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/items', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;
        }

        $item = Item::create([
            'item_name' => $request->input('item_name'),
            'category_id' => $request->input('category_id'),
            'vendor_id' => $user->vendor->id,
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'item_image' => $imagePath,
            'status' => $request->input('status', true),
            'points_status' => $request->input('points_status', false),
            'item_type' => $request->input('item_type'),
        ]);


        $message = "A new item added named: " . $item->item_name;
        $url = "/dashboard/items";
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            if ($admin->onesignal_subs_id) {
                $userId = $admin->onesignal_subs_id;
                $params = [];
                $params['include_player_ids'] = [$userId];
                $params['url'] = $url;
                $params['chrome_web_image'] = asset($item->item_image);
                $contents = [
                    "en" => $message,
                ];
                $params['contents'] = $contents;
                OneSignalFacade::sendNotificationCustom(
                    $params
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'Item created successfully']);
    }

    public function updateStatus($id)
    {
        try {
            // Find the item by ID
            $item = Item::findOrFail($id);

            // Toggle the status (assuming 'status' is a boolean field in your items table)
            $item->status = !$item->status;

            // Save the changes
            $item->save();

            // Return a success response (you can customize this based on your needs)
            return response()->json(['status' => $item->status, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            // Return an error response if an exception occurs
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return view('pages.items.vendor.show', compact(['item']));
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
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $request->validate([
            'item_name' => 'required',
            'category_id' => 'required|exists:item_categories,id',
            'price' => 'required|integer',
            'description' => 'nullable',
            'item_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'boolean',
            'points_status' => 'boolean',
            'item_type' => 'required|string',
        ]);

        $item = Item::findOrFail($id); // Assuming $id is the item ID you want to update

        // Update the image if a new one is provided
        if ($request->hasFile('item_image')) {
            // Delete the existing image
            if ($item->item_image) {
                File::delete($item->item_image);
            }

            // Store the new image
            $image = $request->file('item_image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/items', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;

            $item->update([
                'item_image' => $imagePath,
            ]);
        }

        // Update other fields
        $item->update([
            'item_name' => $request->input('item_name'),
            'category_id' => $request->input('category_id'),
            'vendor_id' => $user->vendor->id,
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'status' => intval($request->input('status', true)),
            'points_status' => $request->input('points_status', false),
            'item_type' => $request->input('item_type'),
        ]);

        $message = $item->item_name . " has been edited!";
        $url = "/dashboard/items/" . $item->id;
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            if ($admin->onesignal_subs_id) {
                $userId = $admin->onesignal_subs_id;
                $params = [];
                $params['include_player_ids'] = [$userId];
                $params['url'] = $url;
                $params['chrome_web_image'] = asset($item->item_image);
                $contents = [
                    "en" => $message,
                ];
                $params['contents'] = $contents;
                OneSignalFacade::sendNotificationCustom(
                    $params
                );
            }
        }
        return redirect('/vendor/items/' . $item->id)->with('success', 'Item updated successfully');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id); // Assuming $id is the item ID you want to delete

        if ($item->item_image) {
            File::delete($item->item_image);
        }

        // Delete the item
        $item->delete();

        // Redirect or respond as needed
        return redirect('/vendor/items')->with('success', 'Item deleted successfully');
    }
}
