<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingCategory;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        $billingCategories = BillingCategory::all();
        return view('pages.finance.admin.clients', compact('clients', 'billingCategories'));
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
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'gst_no' => 'nullable|string|max:15',
        ]);

        Client::create($request->all());

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $clientId = $request->input('id');

        // Retrieve customer data based on customer ID
        $client = Client::find($clientId);

        // Render the drawer card view with customer data
        $drawerContent = view('pages.finance.admin.partials.edit-client-modal-form', ['client' => $client])->render();

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
        // Find the client by ID
        $client = Client::findOrFail($id);

        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'gst_no' => 'nullable|string|max:15',
        ]);

        // Update the client's attributes
        $client->update($request->all());

        return response()->json(['success' => true]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        // Redirect or respond as needed
        return redirect()->back()->with('success', 'Client deleted successfully');
    }
}
