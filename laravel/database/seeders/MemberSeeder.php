<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mainBranch = Branch::first();

        if (!$mainBranch) {
            $this->command->warn('No branch found. Please run BranchSeeder first.');
            return;
        }

        $members = [
            [
                'user_id' => null, // Will create user
                'member_no' => 'M20230001',
                'type' => 'student',
                'id_number' => '1234567890123456',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.univ.ac.id',
                'phone' => '081234567890',
                'address' => 'Jl. Mahasiswa No. 123, Kota Pelajar',
                'status' => 'active',
                'valid_from' => now()->format('Y-m-d'),
                'valid_until' => now()->addYears(4)->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20230002',
                'type' => 'student',
                'id_number' => '1234567890123457',
                'name' => 'Siti Rahayu',
                'email' => 'siti.rahayu@student.univ.ac.id',
                'phone' => '081234567891',
                'address' => 'Jl. Kampus No. 45, Kota Pelajar',
                'status' => 'active',
                'valid_from' => now()->format('Y-m-d'),
                'valid_until' => now()->addYears(4)->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20230003',
                'type' => 'student',
                'id_number' => '1234567890123458',
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@student.univ.ac.id',
                'phone' => '081234567892',
                'address' => 'Asrama Mahasiswa Jl. Asrama No. 10',
                'status' => 'active',
                'valid_from' => now()->format('Y-m-d'),
                'valid_until' => now()->addYears(4)->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20230004',
                'type' => 'student',
                'id_number' => '1234567890123459',
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@student.univ.ac.id',
                'phone' => '081234567893',
                'address' => 'Jl. Perpustakaan No. 78, Kota Pelajar',
                'status' => 'active',
                'valid_from' => now()->format('Y-m-d'),
                'valid_until' => now()->addYears(4)->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20230005',
                'type' => 'student',
                'id_number' => '1234567890123460',
                'name' => 'Rudi Hartono',
                'email' => 'rudi.hartono@student.univ.ac.id',
                'phone' => '081234567894',
                'address' => 'Jl. Fakultas No. 56, Kota Pelajar',
                'status' => 'active',
                'valid_from' => now()->format('Y-m-d'),
                'valid_until' => now()->addYears(4)->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20210001',
                'type' => 'lecturer',
                'id_number' => '987654321000001',
                'name' => 'Dr. Ir. Bambang Suryadi, M.Kom',
                'email' => 'bambang.suryadi@univ.ac.id',
                'phone' => '08111222333',
                'address' => 'Jl. Dosen No. 12, Kota Pelajar',
                'status' => 'active',
                'valid_from' => now()->subYears(2)->format('Y-m-d'),
                'valid_until' => now()->addYears(3)->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20210002',
                'type' => 'lecturer',
                'id_number' => '987654321000002',
                'name' => 'Prof. Dr. Sri Wahyuni, M.Si',
                'email' => 'sri.wahyuni@univ.ac.id',
                'phone' => '08111222334',
                'address' => 'Jl. Profesor No. 8, Kota Pelajar',
                'status' => 'active',
                'valid_from' => now()->subYears(3)->format('Y-m-d'),
                'valid_until' => now()->addYears(2)->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20220001',
                'type' => 'staff',
                'id_number' => '987654321000003',
                'name' => 'Hendra Wijaya',
                'email' => 'hendra.wijaya@univ.ac.id',
                'phone' => '08111222335',
                'address' => 'Jl. Staf No. 23, Kota Pelajar',
                'status' => 'active',
                'valid_from' => now()->subYear()->format('Y-m-d'),
                'valid_until' => now()->addYears(2)->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20220002',
                'type' => 'staff',
                'id_number' => '987654321000004',
                'name' => 'Ratna Sari',
                'email' => 'ratna.sari@univ.ac.id',
                'phone' => '08111222336',
                'address' => 'Jl. Administrasi No. 45, Kota Pelajar',
                'status' => 'active',
                'valid_from' => now()->subYear()->format('Y-m-d'),
                'valid_until' => now()->addYears(2)->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20200001',
                'type' => 'lecturer',
                'id_number' => '987654321000005',
                'name' => 'Dr. Hendra Gunawan, M.Cs',
                'email' => 'hendra.gunawan@research.univ.ac.id',
                'phone' => '08111222337',
                'address' => 'Jl. Peneliti No. 67, Kota Pelajar',
                'status' => 'active',
                'valid_from' => now()->subYears(3)->format('Y-m-d'),
                'valid_until' => now()->addYear()->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20200002',
                'type' => 'lecturer',
                'id_number' => '987654321000006',
                'name' => 'Dr. Linda Pratiwi, M.Eng',
                'email' => 'linda.pratiwi@research.univ.ac.id',
                'phone' => '08111222338',
                'address' => 'Jl. Riset No. 89, Kota Pelajar',
                'status' => 'active',
                'valid_from' => now()->subYears(3)->format('Y-m-d'),
                'valid_until' => now()->addYear()->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20230006',
                'type' => 'student',
                'id_number' => '1234567890123461',
                'name' => 'Fajar Nugraha',
                'email' => 'fajar.nugraha@student.univ.ac.id',
                'phone' => '081234567895',
                'address' => 'Jl. Mahasiswa No. 234, Kota Pelajar',
                'status' => 'suspended', // Suspended member for testing
                'valid_from' => now()->format('Y-m-d'),
                'valid_until' => now()->addYears(4)->format('Y-m-d'),
            ],
            [
                'user_id' => null,
                'member_no' => 'M20190001',
                'type' => 'student',
                'id_number' => '1234567890123462',
                'name' => 'Maya Putri',
                'email' => 'maya.putri@student.univ.ac.id',
                'phone' => '081234567896',
                'address' => 'Jl. Alumni No. 100, Kota Pelajar',
                'status' => 'expired', // Expired member for testing
                'valid_from' => now()->subYears(4)->format('Y-m-d'),
                'valid_until' => now()->subMonths(6)->format('Y-m-d'),
            ],
        ];

        foreach ($members as $memberData) {
            // Create user account for member
            $user = User::create([
                'name' => $memberData['name'],
                'email' => $memberData['email'],
                'password' => Hash::make('password123'), // Default password for testing
                'role' => 'member',
            ]);

            // Remove email from member data as it's in user
            unset($memberData['user_id']);

            // Create member with user_id
            $member = new Member($memberData);
            $member->user_id = $user->id;
            $member->branch_id = $mainBranch->id;
            $member->save();
        }

        $this->command->info('Members seeded successfully.');
        $this->command->info('Default member password: password123');
    }
}
