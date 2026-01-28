<?php

namespace Database\Seeders;

use App\Models\CollectionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collectionTypes = [
            [
                'code' => 'BK',
                'name' => 'Buku Teks',
                'name_en' => 'Textbook',
                'loan_period' => 7,
                'max_renewals' => 2,
                'is_reference' => false,
                'is_loanable' => true,
            ],
            [
                'code' => 'REF',
                'name' => 'Referensi',
                'name_en' => 'Reference',
                'loan_period' => null,
                'max_renewals' => 0,
                'is_reference' => true,
                'is_loanable' => false,
            ],
            [
                'code' => 'JN',
                'name' => 'Jurnal Ilmiah',
                'name_en' => 'Scientific Journal',
                'loan_period' => null,
                'max_renewals' => 0,
                'is_reference' => false,
                'is_loanable' => false,
            ],
            [
                'code' => 'SK',
                'name' => 'Skripsi/Tesis/Disertasi',
                'name_en' => 'Thesis/Dissertation',
                'loan_period' => 3,
                'max_renewals' => 0,
                'is_reference' => false,
                'is_loanable' => true,
            ],
            [
                'code' => 'MM',
                'name' => 'Multimedia',
                'name_en' => 'Multimedia',
                'loan_period' => 3,
                'max_renewals' => 1,
                'is_reference' => false,
                'is_loanable' => true,
            ],
            [
                'code' => 'PM',
                'name' => 'Peta',
                'name_en' => 'Map',
                'loan_period' => 7,
                'max_renewals' => 1,
                'is_reference' => false,
                'is_loanable' => true,
            ],
            [
                'code' => 'NP',
                'name' => 'Naskah Publikasi',
                'name_en' => 'Publication Manuscript',
                'loan_period' => null,
                'max_renewals' => 0,
                'is_reference' => false,
                'is_loanable' => false,
            ],
            [
                'code' => 'KS',
                'name' => 'Karya Seni',
                'name_en' => 'Art Work',
                'loan_period' => null,
                'max_renewals' => 0,
                'is_reference' => true,
                'is_loanable' => false,
            ],
        ];

        foreach ($collectionTypes as $type) {
            CollectionType::create($type);
        }

        $this->command->info('Collection types seeded successfully.');
    }
}
