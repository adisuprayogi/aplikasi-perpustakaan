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

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@kampus.ac.id',
                'password' => Hash::make('password'),
                'branch_id' => $centralBranch->id,
                'role' => 'super_admin',
                'phone' => '081234567890',
                'is_active' => true,
            ],
            [
                'name' => 'Pustakawan Pusat',
                'email' => 'pusat@kampus.ac.id',
                'password' => Hash::make('password'),
                'branch_id' => $centralBranch->id,
                'role' => 'branch_admin',
                'phone' => '081234567891',
                'is_active' => true,
            ],
        ];

        // Add librarians for each faculty branch
        $facultyBranches = ['FKIP', 'FT', 'FH', 'FE'];
        $facultyNames = [
            'FKIP' => 'Pustakawan FKIP',
            'FT' => 'Pustakawan Fakultas Teknik',
            'FH' => 'Pustakawan Fakultas Hukum',
            'FE' => 'Pustakawan Fakultas Ekonomi',
        ];

        $emailSuffix = 2;
        foreach ($facultyBranches as $branchCode) {
            $branch = \App\Models\Branch::where('code', $branchCode)->first();
            $users[] = [
                'name' => $facultyNames[$branchCode],
                'email' => 'lib-' . strtolower($branchCode) . '@kampus.ac.id',
                'password' => Hash::make('password'),
                'branch_id' => $branch->id,
                'role' => 'circulation_staff',
                'phone' => '081234567' . str_pad($emailSuffix, 2, '0', STR_PAD_LEFT),
                'is_active' => true,
            ];
            $emailSuffix++;
        }

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign Spatie role to user
            $user->assignRole($role);
        }

        $this->command->info('Users seeded successfully.');
        $this->command->info('Total users: ' . count($users));
    }
}
