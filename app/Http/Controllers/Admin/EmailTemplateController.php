<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $templates = EmailTemplate::all();
        $currentTemplate = EmailTemplate::where('id', $id)->first();
        return view('pages.email-template.admin.email-template', compact('templates', 'currentTemplate'));
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
        //
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
            'subject' => 'required|string|max:255',
            'body' => 'required',
        ]);

        $template = EmailTemplate::findOrFail($id);
        $template->update($request->only('subject', 'body'));

        return redirect()->back()->with('success', 'Template updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
