<?php

namespace Database\Seeders;

use App\Models\LoanRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            // Students - Default rules for all collection types
            [
                'member_type' => 'student',
                'collection_type_id' => null,
                'loan_period' => 7,
                'max_loans' => 3,
                'fine_per_day' => 500,
                'is_renewable' => true,
                'renew_limit' => 2,
                'is_fine_by_calendar' => true,
                'is_active' => true,
            ],
            // Lecturers - Default rules for all collection types
            [
                'member_type' => 'lecturer',
                'collection_type_id' => null,
                'loan_period' => 14,
                'max_loans' => 5,
                'fine_per_day' => 1000,
                'is_renewable' => true,
                'renew_limit' => 3,
                'is_fine_by_calendar' => true,
                'is_active' => true,
            ],
            // Staff - Default rules for all collection types
            [
                'member_type' => 'staff',
                'collection_type_id' => null,
                'loan_period' => 7,
                'max_loans' => 3,
                'fine_per_day' => 500,
                'is_renewable' => true,
                'renew_limit' => 2,
                'is_fine_by_calendar' => true,
                'is_active' => true,
            ],
            // External - Default rules for all collection types
            [
                'member_type' => 'external',
                'collection_type_id' => null,
                'loan_period' => 7,
                'max_loans' => 2,
                'fine_per_day' => 1000,
                'is_renewable' => false,
                'renew_limit' => 0,
                'is_fine_by_calendar' => true,
                'is_active' => true,
            ],
        ];

        foreach ($rules as $rule) {
            LoanRule::firstOrCreate([
                'member_type' => $rule['member_type'],
                'collection_type_id' => $rule['collection_type_id'],
            ], $rule);
        }

        $this->command->info('Default loan rules created.');
    }
}
