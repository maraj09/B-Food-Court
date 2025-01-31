<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::with('roles')->orderBy('name')->get();
        return view('pages.user-management.admin.permissions', compact('permissions'));
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
        return redirect()->back()->withErrors('Oops, you are not a developer.');
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        // Create the permission
        $permission = Permission::create(['name' => $validated['name']]);

        return redirect()->back()->with('success', 'Permission created successfully.');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return redirect()->back()->withErrors('Oops, you are not a developer.');
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->back()->with('success', 'Permission deleted successfully.');
    }
}
