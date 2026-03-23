<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'Dashboard' => ['Overview' => ['view']],
            'User Management' => [
                'Users' => ['view', 'create', 'edit', 'delete', 'export'],
                'Profile' => ['view', 'update']
            ],
            'Role & Permission' => [
                'Roles' => ['view', 'create', 'edit', 'delete'],
                'Permissions' => ['view', 'assign']
            ],
            'Content' => [
                'Posts' => ['view', 'create', 'edit', 'delete', 'publish'],
                'Media' => ['view', 'upload', 'delete']
            ],
            'Inventory' => [
                'Products' => ['view', 'create', 'edit', 'delete', 'adjust_stock'],
                'Categories' => ['view', 'manage']
            ],
            'Sales' => [
                'Orders' => ['view', 'process', 'cancel', 'refund'],
                'Customers' => ['view', 'manage']
            ],
            'Reports' => [
                'Financial' => ['view', 'export'],
                'Activity' => ['view']
            ],
            'Settings' => [
                'System' => ['view', 'update'],
                'Localization' => ['view', 'update']
            ],
        ];

        foreach ($modules as $moduleName => $features) {
            foreach ($features as $featureName => $actions) {
                foreach ($actions as $action) {
                    $slug = strtolower(str_replace(' ', '_', $moduleName)) . '.' . 
                            strtolower(str_replace(' ', '_', $featureName)) . '.' . 
                            $action;

                    Permission::updateOrCreate(['name' => $slug], [
                        'module' => $moduleName,
                        'feature' => $featureName,
                        'action' => $action,
                    ]);
                }
            }
        }

        // Create Super Admin Role
        $superAdmin = Role::updateOrCreate(['name' => 'Super Admin'], ['is_predefined' => true]);
        $superAdmin->permissions()->sync(Permission::all());

        // Create Viewer Role
        $viewer = Role::updateOrCreate(['name' => 'Viewer'], ['is_predefined' => true]);
        $viewPermissions = Permission::where('action', 'view')->get();
        $viewer->permissions()->sync($viewPermissions);

        // Assign Super Admin to first user or create one
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
        }
        $user->roles()->sync([$superAdmin->id]);
    }
}
