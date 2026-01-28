<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Branch Management
            'branches.view',
            'branches.create',
            'branches.edit',
            'branches.delete',

            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.manage_roles',

            // Member Management
            'members.view',
            'members.create',
            'members.edit',
            'members.delete',
            'members.register',
            'members.renew',
            'members.suspend',

            // Collection Management
            'collections.view',
            'collections.create',
            'collections.edit',
            'collections.delete',
            'collections.import',
            'collections.export',

            // Collection Items
            'items.view',
            'items.create',
            'items.edit',
            'items.delete',
            'items.transfer',

            // Circulation (Loans)
            'loans.view',
            'loans.create',
            'loans.return',
            'loans.renew',
            'loans.overdue',

            // Reservations
            'reservations.view',
            'reservations.create',
            'reservations.cancel',
            'reservations.delete',
            'reservations.manage',

            // Fines & Payments
            'fines.view',
            'fines.waive',
            'payments.view',
            'payments.create',
            'payments.process',

            // Cataloging
            'authors.manage',
            'subjects.manage',
            'publishers.manage',
            'classifications.manage',

            // Reports
            'reports.loans',
            'reports.members',
            'reports.collections',
            'reports.fines',
            'reports.circulation',

            // Settings
            'settings.view',
            'settings.edit',
            'settings.manage',

            // Activity Logs
            'logs.view',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Create roles and assign permissions
        $roles = [
            'super_admin' => ['*'], // All permissions

            'branch_admin' => [
                'dashboard.view',
                'branches.view',
                'users.view',
                'users.create',
                'users.edit',
                'members.view',
                'members.create',
                'members.edit',
                'members.renew',
                'members.suspend',
                'collections.view',
                'items.view',
                'loans.view',
                'loans.create',
                'loans.return',
                'loans.renew',
                'reservations.view',
                'reservations.manage',
                'fines.view',
                'fines.waive',
                'payments.view',
                'payments.process',
                'reports.loans',
                'reports.members',
                'reports.collections',
                'reports.fines',
                'reports.circulation',
                'settings.view',
            ],

            'circulation_staff' => [
                'dashboard.view',
                'members.view',
                'collections.view',
                'items.view',
                'loans.view',
                'loans.create',
                'loans.return',
                'loans.renew',
                'reservations.view',
                'reservations.create',
                'fines.view',
                'payments.view',
                'payments.process',
            ],

            'catalog_staff' => [
                'dashboard.view',
                'collections.view',
                'collections.create',
                'collections.edit',
                'collections.import',
                'collections.export',
                'items.view',
                'items.create',
                'items.edit',
                'authors.manage',
                'subjects.manage',
                'publishers.manage',
                'classifications.manage',
            ],

            'report_viewer' => [
                'dashboard.view',
                'reports.loans',
                'reports.members',
                'reports.collections',
                'reports.fines',
                'reports.circulation',
            ],

            'member' => [
                'dashboard.view',
                'collections.view',
                'loans.view',
                'reservations.view',
                'reservations.create',
                'reservations.cancel',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = \Spatie\Permission\Models\Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            if ($rolePermissions === ['*']) {
                $role->syncPermissions(\Spatie\Permission\Models\Permission::all());
            } else {
                $role->syncPermissions($rolePermissions);
            }
        }

        $this->command->info('Roles and permissions seeded successfully.');
        $this->command->info('Roles: ' . implode(', ', array_keys($roles)));
        $this->command->info('Total permissions: ' . count($permissions));
    }
}
