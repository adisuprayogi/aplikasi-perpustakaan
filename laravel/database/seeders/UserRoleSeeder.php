<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@library.test',
                'role' => 'super_admin',
                'password' => 'super123'
            ],
            [
                'name' => 'Branch Admin',
                'email' => 'branchadmin@library.test',
                'role' => 'branch_admin',
                'password' => 'branch123'
            ],
            [
                'name' => 'Circulation Staff',
                'email' => 'circulation@library.test',
                'role' => 'circulation_staff',
                'password' => 'circulation123'
            ],
            [
                'name' => 'Catalog Staff',
                'email' => 'catalog@library.test',
                'role' => 'catalog_staff',
                'password' => 'catalog123'
            ],
            [
                'name' => 'Report Viewer',
                'email' => 'report@library.test',
                'role' => 'report_viewer',
                'password' => 'report123'
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@library.test',
                'role' => 'admin',
                'password' => 'password123'
            ],
        ];

        foreach ($roles as $userData) {
            // Check if user exists
            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                // Create user
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                ]);

                // Assign role
                $user->assignRole($userData['role']);

                $this->command->info("✓ Created user: {$userData['name']} ({$userData['role']})");
            } else {
                // Update existing user role
                $user->syncRoles([$userData['role']]);
                $this->command->info("✓ Updated role for: {$userData['name']} ({$userData['role']})");
            }
        }

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('USER LOGIN CREDENTIALS:');
        $this->command->info('========================================');

        foreach ($roles as $userData) {
            $this->command->info("{$userData['role']}: {$userData['email']} / {$userData['password']}");
        }

        $this->command->info('========================================');
    }
}
