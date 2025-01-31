<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ItemCategory::all();
        return view('pages.categories.admin.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.categories.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|max:255|unique:item_categories',
            'ribbon_color' => 'required|string|max:50',  // Validation rule for ribbon_color
        ]);

        // Create the new category with ribbon_color
        ItemCategory::create([
            'name' => $request->input('name'),
            'ribbon_color' => $request->input('ribbon_color'),  // Store the ribbon_color
        ]);

        // Redirect to the categories index page with a success message
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(ItemCategory $category)
    {
        return view('pages.categories.admin.create', compact('category'));
    }

    public function update(Request $request, ItemCategory $category)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'ribbon_color' => 'required|string|max:50',
        ]);

        $category->update($request->all());

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemCategory $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Failed to delete category. ' . $e->getMessage());
        }
    }
}
