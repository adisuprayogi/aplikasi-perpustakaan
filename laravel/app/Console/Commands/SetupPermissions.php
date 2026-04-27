<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup roles, permissions, and users for the library system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('===========================================');
        $this->info('  SETUP ROLES & PERMISSIONS');
        $this->info('===========================================');
        $this->newLine();

        // Step 1: Create Permissions
        $this->info('Step 1: Creating 77 permissions...');
        $permissions = [
            ['dashboard.view', 'View dashboard'],
            ['branches.view', 'View branches'],
            ['branches.create', 'Create branches'],
            ['branches.edit', 'Edit branches'],
            ['branches.delete', 'Delete branches'],
            ['users.view', 'View users'],
            ['users.create', 'Create users'],
            ['users.edit', 'Edit users'],
            ['users.delete', 'Delete users'],
            ['users.manage_roles', 'Manage user roles'],
            ['members.view', 'View members'],
            ['members.create', 'Create members'],
            ['members.edit', 'Edit members'],
            ['members.delete', 'Delete members'],
            ['members.register', 'Register members'],
            ['members.renew', 'Renew membership'],
            ['members.suspend', 'Suspend membership'],
            ['collections.view', 'View collections'],
            ['collections.create', 'Create collections'],
            ['collections.edit', 'Edit collections'],
            ['collections.delete', 'Delete collections'],
            ['collections.import', 'Import collections'],
            ['collections.export', 'Export collections'],
            ['items.view', 'View items'],
            ['items.create', 'Create items'],
            ['items.edit', 'Edit items'],
            ['items.delete', 'Delete items'],
            ['items.transfer', 'Transfer items'],
            ['loans.view', 'View loans'],
            ['loans.create', 'Create loans'],
            ['loans.return', 'Return loans'],
            ['loans.renew', 'Renew loans'],
            ['loans.overdue', 'View overdue'],
            ['reservations.view', 'View reservations'],
            ['reservations.create', 'Create reservations'],
            ['reservations.cancel', 'Cancel reservations'],
            ['reservations.manage', 'Manage reservations'],
            ['reservations.delete', 'Delete reservations'],
            ['fines.view', 'View fines'],
            ['fines.waive', 'Waive fines'],
            ['payments.view', 'View payments'],
            ['payments.create', 'Create payments'],
            ['payments.process', 'Process payments'],
            ['payments.view-any', 'View all payments'],
            ['payments.waive', 'Waive payments'],
            ['payments.delete', 'Delete payments'],
            ['authors.manage', 'Manage authors'],
            ['subjects.manage', 'Manage subjects'],
            ['publishers.manage', 'Manage publishers'],
            ['classifications.manage', 'Manage classifications'],
            ['reports.loans', 'View loan reports'],
            ['reports.members', 'View member reports'],
            ['reports.collections', 'View collection reports'],
            ['reports.fines', 'View fine reports'],
            ['reports.circulation', 'View circulation reports'],
            ['settings.view', 'View settings'],
            ['settings.edit', 'Edit settings'],
            ['settings.manage', 'Manage settings'],
            ['logs.view', 'View logs'],
            ['loan-rules.view', 'View loan rules'],
            ['loan-rules.view-any', 'View all loan rules'],
            ['loan-rules.create', 'Create loan rules'],
            ['loan-rules.update', 'Update loan rules'],
            ['loan-rules.delete', 'Delete loan rules'],
            ['digital_files.view', 'View digital files'],
            ['digital_files.create', 'Create digital files'],
            ['digital_files.edit', 'Edit digital files'],
            ['digital_files.delete', 'Delete digital files'],
            ['digital_files.download', 'Download digital files'],
            ['transfers.view', 'View transfers'],
            ['transfers.create', 'Create transfers'],
            ['transfers.manage', 'Manage transfers'],
            ['repositories.view', 'View repositories'],
            ['repositories.create', 'Create repositories'],
            ['repositories.edit', 'Edit repositories'],
            ['repositories.delete', 'Delete repositories'],
            ['repositories.moderate', 'Moderate repositories'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm[0]],
                ['guard_name' => 'web']
            );
        }
        $this->info("  ✓ Created " . count($permissions) . " permissions");
        $this->newLine();

        // Step 2: Create Roles
        $this->info('Step 2: Creating roles...');
        $roles = [
            ['super_admin', 'Super Administrator'],
            ['admin', 'Administrator'],
            ['branch_admin', 'Branch Administrator'],
            ['circulation_staff', 'Circulation Staff'],
            ['catalog_staff', 'Catalog Staff'],
            ['report_viewer', 'Report Viewer'],
            ['member', 'Member'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role[0]],
                ['guard_name' => 'web']
            );
        }
        $this->info("  ✓ Created " . count($roles) . " roles");
        $this->newLine();

        // Step 3: Assign Permissions to Roles
        $this->info('Step 3: Assigning permissions to roles...');

        // super_admin - ALL permissions
        $superAdmin = Role::where('name', 'super_admin')->first();
        $superAdmin->syncPermissions(Permission::all());
        $this->info("  ✓ super_admin: 77 permissions");

        // admin - ALL permissions
        $admin = Role::where('name', 'admin')->first();
        $admin->syncPermissions(Permission::all());
        $this->info("  ✓ admin: 77 permissions");

        // branch_admin - 39 permissions
        $branchAdmin = Role::where('name', 'branch_admin')->first();
        $branchAdminPerms = [
            'dashboard.view', 'branches.view', 'users.view', 'users.create', 'users.edit',
            'members.view', 'members.create', 'members.edit', 'members.renew', 'members.suspend',
            'collections.view', 'items.view', 'loans.view', 'loans.create', 'loans.return', 'loans.renew',
            'reservations.view', 'reservations.manage', 'fines.view', 'fines.waive',
            'payments.view', 'payments.process', 'reports.loans', 'reports.members',
            'reports.collections', 'reports.fines', 'reports.circulation', 'settings.view',
            'digital_files.view', 'digital_files.create', 'digital_files.edit', 'digital_files.delete',
            'transfers.view', 'transfers.create', 'transfers.manage',
            'repositories.view', 'repositories.create', 'repositories.edit', 'repositories.moderate',
        ];
        $branchAdmin->syncPermissions($branchAdminPerms);
        $this->info("  ✓ branch_admin: " . count($branchAdminPerms) . " permissions");

        // circulation_staff - 13 permissions
        $circulation = Role::where('name', 'circulation_staff')->first();
        $circulationPerms = [
            'dashboard.view', 'members.view', 'collections.view', 'items.view',
            'loans.view', 'loans.create', 'loans.return', 'loans.renew',
            'reservations.view', 'reservations.create', 'fines.view', 'payments.view', 'payments.process',
        ];
        $circulation->syncPermissions($circulationPerms);
        $this->info("  ✓ circulation_staff: " . count($circulationPerms) . " permissions");

        // catalog_staff - 19 permissions
        $catalog = Role::where('name', 'catalog_staff')->first();
        $catalogPerms = [
            'dashboard.view', 'collections.view', 'collections.create', 'collections.edit',
            'collections.import', 'collections.export', 'items.view', 'items.create', 'items.edit',
            'authors.manage', 'subjects.manage', 'publishers.manage', 'classifications.manage',
            'digital_files.view', 'digital_files.create', 'digital_files.edit',
            'repositories.view', 'repositories.create', 'repositories.edit',
        ];
        $catalog->syncPermissions($catalogPerms);
        $this->info("  ✓ catalog_staff: " . count($catalogPerms) . " permissions");

        // report_viewer - 6 permissions
        $reportViewer = Role::where('name', 'report_viewer')->first();
        $reportPerms = ['dashboard.view', 'reports.loans', 'reports.members', 'reports.collections', 'reports.fines', 'reports.circulation'];
        $reportViewer->syncPermissions($reportPerms);
        $this->info("  ✓ report_viewer: " . count($reportPerms) . " permissions");

        // member - 6 permissions
        $member = Role::where('name', 'member')->first();
        $memberPerms = ['dashboard.view', 'collections.view', 'loans.view', 'reservations.view', 'reservations.create', 'reservations.cancel'];
        $member->syncPermissions($memberPerms);
        $this->info("  ✓ member: " . count($memberPerms) . " permissions");
        $this->newLine();

        // Step 4: Create/Update Users
        $this->info('Step 4: Setting up users...');
        $branchId = \DB::table('branches')->value('id') ?? 1;

        $users = [
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@library.test',
                'password' => 'super123',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Administrator',
                'email' => 'admin@library.test',
                'password' => 'password123',
                'role' => 'admin',
            ],
            [
                'name' => 'Branch Administrator',
                'email' => 'branchadmin@library.test',
                'password' => 'branch123',
                'role' => 'branch_admin',
            ],
            [
                'name' => 'Circulation Staff',
                'email' => 'circulation@library.test',
                'password' => 'circulation123',
                'role' => 'circulation_staff',
            ],
            [
                'name' => 'Catalog Staff',
                'email' => 'catalog@library.test',
                'password' => 'catalog123',
                'role' => 'catalog_staff',
            ],
            [
                'name' => 'Report Viewer',
                'email' => 'report@library.test',
                'password' => 'report123',
                'role' => 'report_viewer',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'branch_id' => $branchId,
                    'phone' => '081234567890',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Update password
            $user->password = bcrypt($userData['password']);
            $user->save();

            // Assign role
            $user->syncRoles([$userData['role']]);

            $this->info("  ✓ {$userData['name']} ({$userData['role']})");
        }

        $this->newLine();
        $this->info('===========================================');
        $this->info('  LOGIN CREDENTIALS:');
        $this->info('===========================================');
        foreach ($users as $userData) {
            $this->info("{$userData['role']}: {$userData['email']} / {$userData['password']}");
        }
        $this->info('===========================================');
        $this->newLine();

        // Clear cache
        $this->info('Clearing cache...');
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        $this->info("  ✓ Cache cleared");
        $this->newLine();

        $this->info('✅ Setup completed successfully!');
        $this->newLine();
        $this->info('You can now login with the credentials above.');

        return Command::SUCCESS;
    }
}
