<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $modules = \App\Models\Module::all();
        return view('users.create', compact('roles', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_assignments' => 'required|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        foreach ($request->role_assignments as $roleId => $data) {
            if (isset($data['enabled'])) {
                if (empty($data['modules'])) {
                    // Global role
                    $user->roles()->attach($roleId, ['module_id' => null]);
                } else {
                    // Scoped roles
                    foreach ($data['modules'] as $moduleId) {
                        $user->roles()->attach($roleId, ['module_id' => $moduleId]);
                    }
                }
            }
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $modules = \App\Models\Module::all();
        return view('users.edit', compact('user', 'roles', 'modules'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_assignments' => 'required|array',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Re-sync role assignments
        $user->roles()->detach();
        foreach ($request->role_assignments as $roleId => $data) {
            if (isset($data['enabled'])) {
                if (empty($data['modules'])) {
                    $user->roles()->attach($roleId, ['module_id' => null]);
                } else {
                    foreach ($data['modules'] as $moduleId) {
                        $user->roles()->attach($roleId, ['module_id' => $moduleId]);
                    }
                }
            }
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
