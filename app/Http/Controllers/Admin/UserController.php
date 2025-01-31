<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['Customer', 'Vendor']);
        })->get();
        $roles = Role::whereNotIn('name', ['Customer', 'Vendor'])->get();
        return view('pages.user-management.admin.users', compact('users', 'roles'));
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_role' => 'required|exists:roles,id'
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Assign the selected role to the user
        $role = Role::findById($request->input('user_role'));
        $user->assignRole($role);

        // Redirect or return a response
        return redirect()->back()->with('success', 'User created successfully.');
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
        $user = User::findOrFail($id);
        $roles = Role::whereNotIn('name', ['Customer', 'Vendor'])->get();
        if (!$user) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        return response()->json([
            'drawerContent' => view('pages.user-management.admin.partials.edit-user-modal-content', compact('roles', 'user'))->render()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'user_role' => 'required|exists:roles,id',
        ]);

        // Update user details
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password only if it's provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->roles()->sync([$request->user_role]);

        // Save the changes
        $user->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'User updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Check if the user has the admin role and is the only admin
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return redirect()->back()->with('error', 'Cannot delete the only admin.');
        }

        // Proceed with deletion
        $user->roles()->detach(); // Detach all roles from the user
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
