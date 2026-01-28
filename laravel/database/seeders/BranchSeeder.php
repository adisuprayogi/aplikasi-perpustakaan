<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'code' => 'PUSAT',
                'name' => 'Perpustakaan Pusat',
                'type' => 'central',
                'address' => 'Jl. Pendidikan No. 1, Kampus',
                'phone' => '021-123456',
                'email' => 'lib@kampus.ac.id',
                'is_active' => true,
            ],
            [
                'code' => 'FKIP',
                'name' => 'Perpustakaan FKIP',
                'type' => 'faculty',
                'address' => 'Gedung FKIP Lt. 2',
                'phone' => '021-234567',
                'email' => 'lib-fkip@kampus.ac.id',
                'is_active' => true,
            ],
            [
                'code' => 'FT',
                'name' => 'Perpustakaan Fakultas Teknik',
                'type' => 'faculty',
                'address' => 'Gedung Teknik Lt. 1',
                'phone' => '021-345678',
                'email' => 'lib-ft@kampus.ac.id',
                'is_active' => true,
            ],
            [
                'code' => 'FH',
                'name' => 'Perpustakaan Fakultas Hukum',
                'type' => 'faculty',
                'address' => 'Gedung Hukum Lt. 1',
                'phone' => '021-456789',
                'email' => 'lib-fh@kampus.ac.id',
                'is_active' => true,
            ],
            [
                'code' => 'FE',
                'name' => 'Perpustakaan Fakultas Ekonomi',
                'type' => 'faculty',
                'address' => 'Gedung Ekonomi Lt. 2',
                'phone' => '021-567890',
                'email' => 'lib-fe@kampus.ac.id',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }

        $this->command->info('Branches seeded successfully.');
    }
}
