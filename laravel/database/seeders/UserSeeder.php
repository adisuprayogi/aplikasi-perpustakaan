<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the central library branch
        $centralBranch = \App\Models\Branch::where('code', 'PUSAT')->first();

        if (!$centralBranch) {
            $this->command->warn('Central branch not found. Skipping user seeding.');
            return;
        }

        // Define users for all roles
        $users = [
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@library.test',
                'password' => Hash::make('super123'),
                'branch_id' => $centralBranch->id,
                'role' => 'super_admin',
                'phone' => '081234567890',
                'is_active' => true,
            ],
            [
                'name' => 'Administrator',
                'email' => 'admin@library.test',
                'password' => Hash::make('password123'),
                'branch_id' => $centralBranch->id,
                'role' => 'admin',
                'phone' => '081234567891',
                'is_active' => true,
            ],
            [
                'name' => 'Branch Administrator',
                'email' => 'branchadmin@library.test',
                'password' => Hash::make('branch123'),
                'branch_id' => $centralBranch->id,
                'role' => 'branch_admin',
                'phone' => '081234567892',
                'is_active' => true,
            ],
            [
                'name' => 'Circulation Staff',
                'email' => 'circulation@library.test',
                'password' => Hash::make('circulation123'),
                'branch_id' => $centralBranch->id,
                'role' => 'circulation_staff',
                'phone' => '081234567893',
                'is_active' => true,
            ],
            [
                'name' => 'Catalog Staff',
                'email' => 'catalog@library.test',
                'password' => Hash::make('catalog123'),
                'branch_id' => $centralBranch->id,
                'role' => 'catalog_staff',
                'phone' => '081234567894',
                'is_active' => true,
            ],
            [
                'name' => 'Report Viewer',
                'email' => 'report@library.test',
                'password' => Hash::make('report123'),
                'branch_id' => $centralBranch->id,
                'role' => 'report_viewer',
                'phone' => '081234567895',
                'is_active' => true,
            ],
        ];

        $createdCount = 0;
        $updatedCount = 0;

        foreach ($users as $userData) {
            $role = $userData['role'];

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Update the role column if it was set to default 'member'
            if ($user->role === 'member' || $user->role !== $role) {
                $user->role = $role;
                $user->save();
            }

            // Assign Spatie role to user
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }

            if ($user->wasRecentlyCreated) {
                $createdCount++;
            } else {
                $updatedCount++;
            }
        }

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('USER LOGIN CREDENTIALS:');
        $this->command->info('========================================');

        foreach ($users as $userData) {
            $password = match($userData['role']) {
                'super_admin' => 'super123',
                'admin' => 'password123',
                'branch_admin' => 'branch123',
                'circulation_staff' => 'circulation123',
                'catalog_staff' => 'catalog123',
                'report_viewer' => 'report123',
                default => '',
            };
            $this->command->info("{$userData['role']}: {$userData['email']} / {$password}");
        }

        $this->command->info('========================================');
        $this->command->info("Users seeded: {$createdCount} created, {$updatedCount} updated.");
    }
}
