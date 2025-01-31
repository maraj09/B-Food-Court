<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlayArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PlayAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $playAreas = PlayArea::orderBy("id", "desc")->get();
        return view("pages.play-area.admin.play-areas", compact("playAreas"));
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
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'security_deposit' => 'nullable|integer|min:0',
            'max_duration' => 'required|integer|min:1',
            'max_player' => 'required|integer|min:1',
            'details' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/play_areas', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;
        } else {
            return response()->json(['error' => 'Image upload failed.'], 400);
        }

        $playArea = new PlayArea();
        $playArea->title = $validated['title'];
        $playArea->image = $imagePath;
        $playArea->details = $validated['details'];
        $playArea->price = $validated['price'];
        $playArea->security_deposit = $validated['security_deposit'] ?? 0;
        $playArea->max_duration = $validated['max_duration'];
        $playArea->max_player = $validated['max_player'];
        $playArea->status = 1; // Default status, you can change it based on your logic

        // Save the PlayArea instance to the database
        $playArea->save();

        return response()->json(['message' => 'Play area created successfully.'], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            // Find the play area by ID
            $playArea = PlayArea::findOrFail($id);

            // Toggle the status
            $playArea->status = !$playArea->status;

            // Save the updated play area
            $playArea->save();

            return response()->json([
                'message' => 'Play area status updated successfully.',
                'status' => $playArea->status
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating play area status: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update play area status.',
                'error' => $e->getMessage()
            ], 500);
        }
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
        $playArea = PlayArea::findOrFail($id);

        if (!$playArea) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        return response()->json([
            'drawerContent' => view('pages.play-area.admin.partials.edit-play-area-drawer-card', compact('playArea'))->render()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $playArea = PlayArea::findOrFail($id);

        if (!$playArea) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'details' => 'required|string|max:1000',
            'price' => 'required|integer|min:0',
            'security_deposit' => 'nullable|integer|min:0',
            'max_duration' => 'required|integer|min:1',
            'max_player' => 'required|integer|min:1',
        ]);

        // Delete the previous image if it exists
        if ($request->hasFile('image') && $playArea->image) {
            File::delete($playArea->image);
        }

        // Update the play area with validated data
        $playArea->update($validatedData);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/play_areas', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;

            // Update the play area's image field
            $playArea->image = $imagePath;
            $playArea->save();
        }

        return response()->json([
            'message' => 'Play area updated successfully.',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
