<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['modules', 'actions'])->get();
        $modules = \App\Models\Module::with('features.actions')->get();
        return view('roles.index', compact('roles', 'modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'modules' => 'nullable|array',
            'actions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'is_predefined' => false,
        ]);

        // Sync module access
        if ($request->has('modules')) {
            $moduleData = [];
            foreach ($request->modules as $moduleId => $data) {
                if (isset($data['enabled'])) {
                    $moduleData[$moduleId] = ['full_access' => isset($data['full_access'])];
                }
            }
            $role->modules()->sync($moduleData);
        }

        // Sync specific actions
        if (!empty($validated['actions'])) {
            $role->actions()->sync($validated['actions']);
        }

        return redirect()->back()->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        if ($role->is_predefined && $role->name === 'Super Admin') {
            return redirect()->back()->with('error', 'Super Admin role cannot be modified.');
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'modules' => 'nullable|array',
            'actions' => 'nullable|array',
        ]);

        $role->update(['name' => $validated['name']]);

        // Sync module access
        $moduleData = [];
        if ($request->has('modules')) {
            foreach ($request->modules as $moduleId => $data) {
                if (isset($data['enabled'])) {
                    $moduleData[$moduleId] = ['full_access' => isset($data['full_access'])];
                }
            }
        }
        $role->modules()->sync($moduleData);

        // Sync specific actions
        $role->actions()->sync($validated['actions'] ?? []);

        return redirect()->back()->with('success', 'Role updated successfully.');
    }
}
