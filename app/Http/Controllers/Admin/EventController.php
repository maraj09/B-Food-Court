<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::orderBy("id", "desc")->get();
        return view('pages.event.admin.events', compact('events'));
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
            'start_date' => 'required|date_format:Y-m-d H:i',
            'end_date' => 'required|date_format:Y-m-d H:i|after_or_equal:start_date',
            'price' => 'required|integer|min:0',
            'seats' => 'required|integer|min:1',
            'details' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/events', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;
        } else {
            return response()->json(['error' => 'Image upload failed.'], 400);
        }

        $event = new Event();
        $event->title = $validated['title'];
        $event->image = $imagePath;
        $event->details = $validated['details'];
        $event->price = $validated['price'];
        $event->start_date = $validated['start_date'];
        $event->end_date = $validated['end_date'];
        $event->seats = $validated['seats'];
        $event->status = 1; // Default status, you can change it based on your logic

        // Save the Event instance to the database
        $event->save();

        return response()->json(['message' => 'Event created successfully.'], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            // Find the play area by ID
            $event = Event::findOrFail($id);

            // Toggle the status
            $event->status = !$event->status;

            // Save the updated play area
            $event->save();

            return response()->json([
                'message' => 'Event status updated successfully.',
                'status' => $event->status
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating play area status: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update play area status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateOrderEventAttendeeStatus(Request $request, $id)
    {
        try {
            // Find the play area by ID
            $orderEvent = OrderItem::findOrFail($id);

            // Toggle the status
            $orderEvent->event_attendee_arrived = !$orderEvent->event_attendee_arrived;

            // Save the updated play area
            $orderEvent->save();

            return response()->json([
                'message' => 'Attendee status updated successfully.',
                'status' => $orderEvent->event_attendee_arrived
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
        $event = Event::findOrFail($id);

        if (!$event) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        return response()->json([
            'drawerContent' => view('pages.event.admin.partials.edit-event-drawer-card', compact('event'))->render()
        ]);
    }

    public function attendees(string $id)
    {
        $event = Event::findOrFail($id);
        $orderEvents = OrderItem::whereNot('status', 'unpaid')->where('event_id', $id)->get();

        if (!$event) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        return response()->json([
            'drawerContent' => view('pages.event.admin.partials.event-booking-drawer-cart', compact('event', 'orderEvents'))->render()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        if (!$event) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date_format:Y-m-d H:i',
            'end_date' => 'required|date_format:Y-m-d H:i|after_or_equal:start_date',
            'price' => 'required|integer|min:0',
            'seats' => 'required|integer|min:1',
            'details' => 'required|string',
        ]);

        // Delete the previous image if it exists
        if ($request->hasFile('image') && $event->image) {
            File::delete($event->image);
        }

        // Update the play area with validated data
        $event->update($validated);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/events', $imageName, 'public');
            $imagePath = 'storage/' . $imagePath;

            // Update the play area's image field
            $event->image = $imagePath;
            $event->save();
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
