<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BillingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $billingCategories = BillingCategory::all();
        return view('pages.finance.admin.billing-categories', compact('billingCategories'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gst_no' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'color_class' => 'required|string|max:255',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageName = date('Ymd_His') . '-' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('images/logos', $imageName, 'public');
            $logoPath = 'storage/' . $imagePath;
        }

        BillingCategory::create([
            'name' => $request->input('name'),
            'logo' => $logoPath,
            'gst_no' => $request->input('gst_no'),
            'address' => $request->input('address'),
            'color_class' => $request->input('color_class'),
        ]);

        return redirect()->back()->with('success', 'Billing category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $categoryId = $request->input('id');

        // Retrieve customer data based on customer ID
        $billingCategory = BillingCategory::find($categoryId);

        // Render the drawer card view with customer data
        $drawerContent = view('pages.finance.admin.partials.edit-billing-categories-drawer-form', ['billingCategory' => $billingCategory])->render();

        // Return the rendered drawer card HTML as part of the AJAX response
        return response()->json(['drawerContent' => $drawerContent]);
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
        $category = BillingCategory::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gst_no' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'color_class' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // Max size 2MB
        ]);

        // Handle the logo upload if a new file is provided
        if ($request->hasFile('logo')) {
            // Delete the old logo file if it exists
            if ($category->logo) {
                File::delete($category->logo);
            }

            // Store the new logo
            $image = $request->file('logo');
            $imageName = date('Ymd_His') . '-' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('images/billing_categories', $imageName, 'public');
            $validatedData['logo'] = 'storage/' . $imagePath; // Store the path in validated data
        } else {
            // If no new logo is uploaded, retain the old logo path
            $validatedData['logo'] = $category->logo;
        }

        if ($request->avatar_remove == 1) {
            if ($category->logo) {
                File::delete($category->logo);
            }
            $validatedData['logo'] = null;
        }

        // Update the category with validated data
        $category->update($validatedData);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillingCategory $billingCategory)
    {
        // Delete the item
        if (!empty($billingCategory->logo)) {
            File::delete($billingCategory->logo);
        }

        $billingCategory->delete();

        // Redirect or respond as needed
        return redirect()->back()->with('success', 'Category deleted successfully');
    }
}
