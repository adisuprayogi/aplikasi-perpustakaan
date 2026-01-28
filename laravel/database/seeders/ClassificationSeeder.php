<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DDC (Dewey Decimal Classification) Main Classes
        $classifications = [
            ['code' => '000', 'name' => 'Karya Umum', 'name_en' => 'General Works'],
            ['code' => '100', 'name' => 'Filsafat & Psikologi', 'name_en' => 'Philosophy & Psychology'],
            ['code' => '200', 'name' => 'Agama', 'name_en' => 'Religion'],
            ['code' => '300', 'name' => 'Ilmu Sosial', 'name_en' => 'Social Sciences'],
            ['code' => '400', 'name' => 'Bahasa', 'name_en' => 'Language'],
            ['code' => '500', 'name' => 'Sains', 'name_en' => 'Science'],
            ['code' => '600', 'name' => 'Teknologi', 'name_en' => 'Technology'],
            ['code' => '700', 'name' => 'Seni & Rekreasi', 'name_en' => 'Arts & Recreation'],
            ['code' => '800', 'name' => 'Sastra', 'name_en' => 'Literature'],
            ['code' => '900', 'name' => 'Sejarah & Geografi', 'name_en' => 'History & Geography'],
        ];

        // Add some subdivisions for common subjects
        $subdivisions = [
            // 300 - Ilmu Sosial
            ['code' => '370', 'name' => 'Pendidikan', 'name_en' => 'Education'],
            ['code' => '370.1', 'name' => 'Filsafat Pendidikan', 'name_en' => 'Philosophy of Education'],
            ['code' => '370.15', 'name' => 'Psikologi Pendidikan', 'name_en' => 'Educational Psychology'],
            ['code' => '371', 'name' => 'Manajemen Sekolah', 'name_en' => 'School Management'],
            ['code' => '371.3', 'name' => 'Metode Pengajaran', 'name_en' => 'Teaching Methods'],
            ['code' => '372', 'name' => 'Pendidikan Dasar', 'name_en' => 'Primary Education'],

            // 600 - Teknologi
            ['code' => '620', 'name' => 'Teknik Rekayasa', 'name_en' => 'Engineering'],
            ['code' => '621', 'name' => 'Teknik Mesin', 'name_en' => 'Mechanical Engineering'],
            ['code' => '624', 'name' => 'Teknik Sipil', 'name_en' => 'Civil Engineering'],
            ['code' => '629', 'name' => 'Teknik Elektro', 'name_en' => 'Electrical Engineering'],
            ['code' => '657', 'name' => 'Akuntansi', 'name_en' => 'Accounting'],
            ['code' => '658', 'name' => 'Manajemen', 'name_en' => 'Management'],

            // 340 - Hukum
            ['code' => '340', 'name' => 'Hukum', 'name_en' => 'Law'],
            ['code' => '341', 'name' => 'Hukum Internasional', 'name_en' => 'International Law'],
            ['code' => '345', 'name' => 'Hukum Pidana', 'name_en' => 'Criminal Law'],
            ['code' => '346', 'name' => 'Hukum Perdata', 'name_en' => 'Private Law'],

            // 500 - Sains
            ['code' => '510', 'name' => 'Matematika', 'name_en' => 'Mathematics'],
            ['code' => '530', 'name' => 'Fisika', 'name_en' => 'Physics'],
            ['code' => '540', 'name' => 'Kimia', 'name_en' => 'Chemistry'],
            ['code' => '550', 'name' => 'Ilmu Kebumian', 'name_en' => 'Earth Sciences'],
            ['code' => '570', 'name' => 'Biologi', 'name_en' => 'Biology'],

            // 800 - Sastra
            ['code' => '810', 'name' => 'Sastra Amerika', 'name_en' => 'American Literature'],
            ['code' => '820', 'name' => 'Sastra Inggris', 'name_en' => 'English Literature'],
            ['code' => '899', 'name' => 'Sastra Indonesia', 'name_en' => 'Indonesian Literature'],
        ];

        $allClassifications = array_merge($classifications, $subdivisions);

        foreach ($allClassifications as $classification) {
            Classification::create($classification);
        }

        $this->command->info('Classifications seeded successfully.');
    }
}
