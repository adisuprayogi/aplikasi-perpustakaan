<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // Education
            ['code' => 'EDU', 'name' => 'Pendidikan', 'name_en' => 'Education'],
            ['code' => 'EDU-PHI', 'name' => 'Filsafat Pendidikan', 'name_en' => 'Philosophy of Education'],
            ['code' => 'EDU-PSY', 'name' => 'Psikologi Pendidikan', 'name_en' => 'Educational Psychology'],
            ['code' => 'EDU-MGT', 'name' => 'Manajemen Pendidikan', 'name_en' => 'Educational Management'],
            ['code' => 'EDU-CUR', 'name' => 'Kurikulum', 'name_en' => 'Curriculum'],
            ['code' => 'EDU-MTH', 'name' => 'Metode Pembelajaran', 'name_en' => 'Teaching Methods'],

            // Engineering
            ['code' => 'ENG', 'name' => 'Teknik', 'name_en' => 'Engineering'],
            ['code' => 'ENG-MECH', 'name' => 'Teknik Mesin', 'name_en' => 'Mechanical Engineering'],
            ['code' => 'ENG-CIV', 'name' => 'Teknik Sipil', 'name_en' => 'Civil Engineering'],
            ['code' => 'ENG-ELEC', 'name' => 'Teknik Elektro', 'name_en' => 'Electrical Engineering'],
            ['code' => 'ENG-CHE', 'name' => 'Teknik Kimia', 'name_en' => 'Chemical Engineering'],
            ['code' => 'ENG-IND', 'name' => 'Teknik Industri', 'name_en' => 'Industrial Engineering'],
            ['code' => 'ENG-ENV', 'name' => 'Teknik Lingkungan', 'name_en' => 'Environmental Engineering'],

            // Law
            ['code' => 'LAW', 'name' => 'Hukum', 'name_en' => 'Law'],
            ['code' => 'LAW-PEN', 'name' => 'Hukum Pidana', 'name_en' => 'Criminal Law'],
            ['code' => 'LAW-CIV', 'name' => 'Hukum Perdata', 'name_en' => 'Civil Law'],
            ['code' => 'LAW-INT', 'name' => 'Hukum Internasional', 'name_en' => 'International Law'],
            ['code' => 'LAW-ADM', 'name' => 'Hukum Administrasi', 'name_en' => 'Administrative Law'],
            ['code' => 'LAW-BUS', 'name' => 'Hukum Bisnis', 'name_en' => 'Business Law'],

            // Economics & Business
            ['code' => 'ECO', 'name' => 'Ekonomi', 'name_en' => 'Economics'],
            ['code' => 'ECO-MAC', 'name' => 'Ekonomi Makro', 'name_en' => 'Macroeconomics'],
            ['code' => 'ECO-MIC', 'name' => 'Ekonomi Mikro', 'name_en' => 'Microeconomics'],
            ['code' => 'ECO-DEV', 'name' => 'Ekonomi Pembangunan', 'name_en' => 'Development Economics'],
            ['code' => 'ACC', 'name' => 'Akuntansi', 'name_en' => 'Accounting'],
            ['code' => 'MGT', 'name' => 'Manajemen', 'name_en' => 'Management'],
            ['code' => 'MGT-MKT', 'name' => 'Pemasaran', 'name_en' => 'Marketing'],
            ['code' => 'MGT-FIN', 'name' => 'Keuangan', 'name_en' => 'Finance'],
            ['code' => 'MGT-HR', 'name' => 'Manajemen SDM', 'name_en' => 'Human Resource Management'],

            // Science
            ['code' => 'SCI', 'name' => 'Sains', 'name_en' => 'Science'],
            ['code' => 'MAT', 'name' => 'Matematika', 'name_en' => 'Mathematics'],
            ['code' => 'PHY', 'name' => 'Fisika', 'name_en' => 'Physics'],
            ['code' => 'CHE', 'name' => 'Kimia', 'name_en' => 'Chemistry'],
            ['code' => 'BIO', 'name' => 'Biologi', 'name_en' => 'Biology'],
            ['code' => 'EAR', 'name' => 'Ilmu Kebumian', 'name_en' => 'Earth Sciences'],
            ['code' => 'CMP', 'name' => 'Ilmu Komputer', 'name_en' => 'Computer Science'],

            // Literature
            ['code' => 'LIT', 'name' => 'Sastra', 'name_en' => 'Literature'],
            ['code' => 'LIT-IND', 'name' => 'Sastra Indonesia', 'name_en' => 'Indonesian Literature'],
            ['code' => 'LIT-ENG', 'name' => 'Sastra Inggris', 'name_en' => 'English Literature'],
            ['code' => 'LIT-ARA', 'name' => 'Sastra Arab', 'name_en' => 'Arabic Literature'],
            ['code' => 'LIT-JPN', 'name' => 'Sastra Jepang', 'name_en' => 'Japanese Literature'],

            // Technology
            ['code' => 'TEC', 'name' => 'Teknologi', 'name_en' => 'Technology'],
            ['code' => 'TEC-INF', 'name' => 'Teknologi Informasi', 'name_en' => 'Information Technology'],
            ['code' => 'TEC-AI', 'name' => 'Kecerdasan Buatan', 'name_en' => 'Artificial Intelligence'],
            ['code' => 'TEC-DAT', 'name' => 'Sains Data', 'name_en' => 'Data Science'],
            ['code' => 'TEC-CLO', 'name' => 'Computing Awan', 'name_en' => 'Cloud Computing'],

            // Social Sciences
            ['code' => 'SOC', 'name' => 'Ilmu Sosial', 'name_en' => 'Social Sciences'],
            ['code' => 'SOC-SOC', 'name' => 'Sosiologi', 'name_en' => 'Sociology'],
            ['code' => 'SOC-PSY', 'name' => 'Psikologi', 'name_en' => 'Psychology'],
            ['code' => 'SOC-ANT', 'name' => 'Antropologi', 'name_en' => 'Anthropology'],
            ['code' => 'SOC-POL', 'name' => 'Ilmu Politik', 'name_en' => 'Political Science'],
            ['code' => 'SOC-COM', 'name' => 'Ilmu Komunikasi', 'name_en' => 'Communication Studies'],

            // Religion
            ['code' => 'REL', 'name' => 'Agama', 'name_en' => 'Religion'],
            ['code' => 'REL-ISL', 'name' => 'Studi Islam', 'name_en' => 'Islamic Studies'],
            ['code' => 'REL-CHR', 'name' => 'Studi Kristen', 'name_en' => 'Christian Studies'],
            ['code' => 'REL-HIN', 'name' => 'Studi Hindu', 'name_en' => 'Hindu Studies'],
            ['code' => 'REL-BUD', 'name' => 'Studi Buddha', 'name_en' => 'Buddhist Studies'],

            // Arts
            ['code' => 'ART', 'name' => 'Seni', 'name_en' => 'Arts'],
            ['code' => 'ART-VIS', 'name' => 'Seni Rupa', 'name_en' => 'Visual Arts'],
            ['code' => 'ART-MUS', 'name' => 'Musik', 'name_en' => 'Music'],
            ['code' => 'ART-THE', 'name' => 'Teater', 'name_en' => 'Theater'],
            ['code' => 'ART-DAN', 'name' => 'Tari', 'name_en' => 'Dance'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $this->command->info('Subjects seeded successfully.');
    }
}
