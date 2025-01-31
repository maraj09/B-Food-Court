<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceTax;
use Illuminate\Http\Request;

class InvoiceTaxController extends Controller
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
            'tax_title' => 'required|string|max:255',
            'tax_rate' => 'required|numeric',
        ]);

        InvoiceTax::create($request->all());

        return redirect()->back()->with('success', 'Tax added successfully!');
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
    public function edit($id)
    {
        $invoiceTax = InvoiceTax::findOrFail($id);

        if (!$invoiceTax) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        return response()->json([
            'drawerContent' => view('pages.settings.admin.partials.edit-tax-modal-form', compact('invoiceTax'))->render()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $invoiceTax = InvoiceTax::findOrFail($id);
        $request->validate([
            'tax_title' => 'sometimes|required|string|max:255',
            'tax_rate' => 'sometimes|required|numeric',
        ]);

        // Update the invoice tax with the new data
        $invoiceTax->update($request->all());
        return redirect()->back()->with('success', 'Tax updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoiceTax = InvoiceTax::findOrFail($id);
        $invoiceTax->delete();
        return redirect()->back()->with('success', 'Tax updated successfully!');
    }
}
