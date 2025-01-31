<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Coderflex\LaravelTicket\Models\Category;
use Coderflex\LaravelTicket\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $categories = Category::first();
        $ticket = $user->tickets()
            ->create([
                'title' => $request->subject,
            ]);
        $ticket->attachCategories($categories);
        $ticket->messages()->create([
            'user_id' => $user->id,
            'message' => $request->description
        ]);

        return redirect()->back()->with('success', 'Ticket Created Successfully!');
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
        $request->validate([
            'message' => 'required|string',
        ]);
        $ticket = Ticket::where('id', $id)->first();
        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);
        if ($ticket->status == 'closed') {
            $ticket->reopen();
        }
        return redirect()->back()->with('success', 'Message Send Successfully!');
    }

    public function updateStatus(string $id)
    {
        $ticket = Ticket::where('id', $id)->first();
        $ticket->close();
        return redirect()->back()->with('success', 'Message is Closed!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
