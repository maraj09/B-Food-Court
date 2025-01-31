<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Vendor;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        $vendors = Vendor::all();
        return view('pages.items.admin.item', compact(['items', 'vendors']));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'category_id' => 'required|exists:item_categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'price' => 'required|integer',
            'description' => 'nullable',
            'item_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'boolean',
            'points_status' => 'boolean',
            'item_type' => 'required|string',
        ]);

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
            'vendor_id' => $request->input('vendor_id'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'item_image' => $imagePath,
            'status' => $request->input('status', true),
            'points_status' => $request->input('points_status', false),
            'item_type' => $request->input('item_type'),
            'approve' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Item created successfully']);
    }


    public function updateApprove($id)
    {
        try {
            // Find the item by ID
            $item = Item::findOrFail($id);

            // Toggle the status (assuming 'status' is a boolean field in your items table)
            $item->approve = !$item->approve;

            // Save the changes
            $item->save();

            if ($item->approve == 1) {
                $message = "You item named: " . $item->item_name . " has been approved.";
            } else {
                $message = "You item named: " . $item->item_name . " is not live any more!";
            }

            $url = "/vendor/items";
            $vendor = $item->vendor->user;
            if ($vendor->onesignal_subs_id) {
                $userId = $vendor->onesignal_subs_id;
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


            // Return a success response (you can customize this based on your needs)
            return response()->json(['status' => $item->approve, 'message' => 'Item approved successfully']);
        } catch (\Exception $e) {
            // Return an error response if an exception occurs
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Item $item)
    {
        $vendors = Vendor::all();
        return view('pages.items.admin.show', compact(['vendors', 'item']));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required',
            'category_id' => 'required|exists:item_categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'price' => 'required|integer',
            'description' => 'nullable',
            'item_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'vendor_id' => $request->input('vendor_id'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'status' => intval($request->input('status', true)),
            'points_status' => $request->input('points_status', false),
            'item_type' => $request->input('item_type'),
        ]);
        return redirect('/dashboard/items/' . $item->id)->with('success', 'Item updated successfully');
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
        return redirect('/dashboard/items')->with('success', 'Item deleted successfully');
    }
}
