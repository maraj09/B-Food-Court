<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->get();
        $allPermissions = Permission::with('roles')->orderBy('name')->get();
        return view('pages.user-management.admin.roles', compact('roles', 'allPermissions'));
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
            'role_name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        // Create the role
        $role = Role::create(['name' => $request->input('role_name')]);

        // Assign permissions to the role
        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }

        return redirect()->back()->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $allPermissions = Permission::all();
        return view('pages.user-management.admin.view-role', compact('role', 'allPermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $allPermissions = Permission::with('roles')->orderBy('name')->get();
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        return response()->json([
            'drawerContent' => view('pages.user-management.admin.partials.edit-role-modal-form', compact('role', 'allPermissions'))->render()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Check if the role is 'admin', 'vendor', or 'customer'
        if (in_array($role->name, ['admin', 'vendor', 'customer'])) {
            return redirect()->back()->with('error', 'You cannot update the ' . $role->name . ' role.');
        }

        // Validate the request data
        $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        // Update the role name
        $role->name = $request->input('role_name');
        $role->save();

        // Sync the role's permissions
        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        } else {
            // If no permissions were selected, clear all existing permissions
            $role->syncPermissions([]);
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Role updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Check if the role is 'admin', 'vendor', or 'customer'
        if (in_array($role->name, ['admin', 'vendor', 'customer'])) {
            return redirect()->back()->with('error', 'You cannot delete the ' . $role->name . ' role.');
        }

        // Delete the role
        $role->delete();

        // Redirect back with success message
        return redirect('/dashboard/user-management/roles')->with('success', 'Role deleted successfully.');
    }
}
