<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Module;
use App\Models\Feature;
use App\Models\Action;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear old data to prevent pivot issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_modules')->truncate();
        DB::table('role_actions')->truncate();
        DB::table('user_role')->truncate();
        DB::table('actions')->truncate();
        DB::table('features')->truncate();
        DB::table('modules')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $modules = [
            'Vehicle Management' => [
                'Profiles' => ['view', 'create', 'edit', 'delete'],
                'Maintenance' => ['view', 'log', 'schedule'],
                'Fuel' => ['log', 'report'],
                'Documents' => ['view', 'renew']
            ],
            'Security Management' => [
                'Vendors' => ['view', 'manage'],
                'Guards' => ['view', 'manage'],
                'Schedules' => ['view', 'assign'],
                'Incidents' => ['log', 'view', 'report'],
                'Training' => ['view', 'manage']
            ],
            'Overseas Visa Management' => [
                'Requirements' => ['view', 'manage'],
                'Checklist' => ['view', 'manage'],
                'Applications' => ['track', 'update', 'view']
            ],
            'BGD Visa Management' => [
                'Expats' => ['view', 'manage'],
                'Work Permits' => ['view', 'apply', 'renew', 'verify'],
                'Security Clearances' => ['view', 'update'],
                'Alerts' => ['view', 'notify']
            ],
            'IL, BRL & Hotel Management' => [
                'Hotels' => ['view', 'manage'],
                'Reservations' => ['view', 'track', 'book', 'verify'],
                'Uploads' => ['view', 'upload'],
                'Cost Reporting' => ['view', 'export']
            ],
            'Air Ticket Management' => [
                'Travel Schedules' => ['view', 'manage'],
                'Ticket Uploads' => ['view', 'upload'],
                'Vendor Reports' => ['view', 'export']
            ],
            'Expats Apartment Management' => [
                'Maintenance' => ['view', 'log', 'cost_report'],
                'Bill Uploads' => ['view', 'upload'],
                'History' => ['view']
            ],
            'Office Administrative Management' => [
                'Expenses' => ['view', 'log', 'report'],
                'Bill Approval' => ['view', 'approve', 'reject'],
                'Workflows' => ['manage']
            ],
        ];

        foreach ($modules as $moduleName => $features) {
            $module = Module::updateOrCreate(['name' => $moduleName]);

            foreach ($features as $featureName => $actions) {
                $feature = Feature::updateOrCreate([
                    'module_id' => $module->id,
                    'name' => $featureName,
                ]);

                foreach ($actions as $action) {
                    $slug = strtolower(str_replace([' ', '&', ','], '_', $moduleName)) . '.' . 
                            strtolower(str_replace([' ', '&', ','], '_', $featureName)) . '.' . 
                            $action;

                    Action::updateOrCreate([
                        'feature_id' => $feature->id,
                        'slug' => $slug,
                    ], [
                        'name' => $action,
                    ]);
                }
            }
        }

        // --- REFINED GENERAL ROLES ---

        // 1. Admin (Full Access)
        $adminRole = Role::updateOrCreate(['name' => 'Admin'], ['is_predefined' => true]);
        $adminRole->actions()->sync(Action::all()->pluck('id'));
        foreach (Module::all() as $m) $adminRole->modules()->syncWithoutDetaching([$m->id => ['full_access' => true]]);

        // 2. Manager/Approver (Review & Approve)
        $managerRole = Role::updateOrCreate(['name' => 'Manager/Approver'], ['is_predefined' => true]);
        $managerActions = Action::whereIn('name', ['view', 'approve', 'reject', 'report', 'track'])->pluck('id');
        $managerRole->actions()->sync($managerActions);

        // 3. Checker (Verify Data Accuracy)
        $checkerRole = Role::updateOrCreate(['name' => 'Checker'], ['is_predefined' => true]);
        $checkerActions = Action::whereIn('name', ['view', 'track', 'verify'])->pluck('id');
        $checkerRole->actions()->sync($checkerActions);

        // 4. Coordinator (General Management & Tracking)
        $coordinatorRole = Role::updateOrCreate(['name' => 'Coordinator'], ['is_predefined' => true]);
        $coordActions = Action::whereIn('name', ['view', 'manage', 'track', 'update', 'apply', 'renew', 'log', 'assign', 'create', 'edit'])->pluck('id');
        $coordinatorRole->actions()->sync($coordActions);

        // 5. Input User (Basic Data Entry)
        $inputRole = Role::updateOrCreate(['name' => 'Input User'], ['is_predefined' => true]);
        $inputActions = Action::whereIn('name', ['view', 'create', 'update', 'book', 'upload', 'log'])->pluck('id');
        $inputRole->actions()->sync($inputActions);

        // 6. Compliance Officer (Monitor Deadlines)
        $complianceRole = Role::updateOrCreate(['name' => 'Compliance Officer'], ['is_predefined' => true]);
        $complianceActions = Action::whereIn('name', ['view', 'alerts', 'notify', 'renew'])->pluck('id');
        $complianceRole->actions()->sync($complianceActions);

        // 7. Viewer (Read-only)
        $viewerRole = Role::updateOrCreate(['name' => 'Viewer'], ['is_predefined' => true]);
        $viewActions = Action::where('name', 'view')->pluck('id');
        $viewerRole->actions()->sync($viewActions);


        // --- USERS SEEDING WITH SCOPING ---

        // Super Admin (Developer)
        $superAdminRole = Role::updateOrCreate(['name' => 'Super Admin'], ['is_predefined' => true]);
        $superAdminRole->actions()->sync(Action::all()->pluck('id'));
        foreach (Module::all() as $m) $superAdminRole->modules()->syncWithoutDetaching([$m->id => ['full_access' => true]]);

        $dev = User::updateOrCreate(['email' => 'dev@example.com'], [
            'name' => 'Developer',
            'password' => Hash::make('password'),
        ]);
        $dev->roles()->sync([$superAdminRole->id]);

        // Admin User (Standard Admin)
        $admin = User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'System Admin',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->sync([$adminRole->id]);

        // Rahim (Coordinator Role scoped to Security Management)
        $rahim = User::updateOrCreate(['email' => 'rahim@example.com'], [
            'name' => 'Rahim',
            'password' => Hash::make('password'),
        ]);
        $securityModule = Module::where('name', 'Security Management')->first();
        $rahim->roles()->detach();
        $rahim->roles()->attach($coordinatorRole->id, ['module_id' => $securityModule->id]);

        // Karim (Coordinator Role scoped to Overseas Visa Management)
        $karim = User::updateOrCreate(['email' => 'karim@example.com'], [
            'name' => 'Karim',
            'password' => Hash::make('password'),
        ]);
        $overseasVisa = Module::where('name', 'Overseas Visa Management')->first();
        $karim->roles()->detach();
        $karim->roles()->attach($coordinatorRole->id, ['module_id' => $overseasVisa->id]);
    }
}
