<?php

namespace Database\Seeders;

use App\Models\Classification;
use App\Models\Collection;
use App\Models\CollectionType;
use App\Models\Gmd;
use App\Models\Publisher;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get reference data IDs
        $textbookType = CollectionType::where('code', 'BK')->first();
        $referenceType = CollectionType::where('code', 'REF')->first();
        $thesisType = CollectionType::where('code', 'SK')->first();

        $publisherGramedia = Publisher::where('name', 'Gramedia Pustaka Utama')->first();
        $publisherErlangga = Publisher::where('name', 'Erlangga')->first();
        $publisherUI = Publisher::where('name', 'Penerbit Universitas Indonesia')->first();
        $publisherSpringer = Publisher::where('name', 'Springer')->first();

        $generalClassification = Classification::where('code', '000')->first();
        $educationClassification = Classification::where('code', '370')->first() ?? Classification::first();
        $csClassification = Classification::where('code', '004')->first() ?? Classification::first();

        $bookGmd = Gmd::where('code', 'BK')->first() ?? Gmd::first();

        // Get subjects
        $subjects = Subject::take(5)->get();

        $collections = [
            [
                'title' => 'Pemrograman Web Modern dengan Laravel',
                'authors' => ['Ahmad Supriyadi', 'Siti Aminah'],
                'isbn' => '978-602-03-1234-5',
                'publisher_id' => $publisherErlangga?->id ?? 1,
                'year' => 2023,
                'edition' => 'Edisi 2',
                'pages' => 450,
                'language' => 'id',
                'classification_id' => $csClassification?->id ?? 1,
                'collection_type_id' => $textbookType?->id ?? 1,
                'gmd_id' => $bookGmd?->id ?? 1,
                'call_number' => '005.5 SUP p',
                'abstract' => 'Buku ini membahas pengembangan aplikasi web modern menggunakan framework Laravel versi terbaru. Mencakup konsep MVC, Eloquent ORM, Blade templating, dan fitur-fitur modern Laravel.',
                'description' => 'Panduan lengkap untuk pengembang web yang ingin menguasai Laravel.',
                'total_items' => 3,
                'available_items' => 3,
                'borrowed_items' => 0,
                'price' => 125000,
            ],
            [
                'title' => 'Algoritma dan Struktur Data',
                'authors' => ['Rinaldi Munir'],
                'isbn' => '978-979-29-1234-6',
                'publisher_id' => $publisherUI?->id ?? 2,
                'year' => 2022,
                'edition' => 'Edisi 4',
                'pages' => 520,
                'language' => 'id',
                'classification_id' => $csClassification?->id ?? 1,
                'collection_type_id' => $textbookType?->id ?? 1,
                'gmd_id' => $bookGmd?->id ?? 1,
                'call_number' => '005.1 MUN a',
                'abstract' => 'Buku referensi komprehensif tentang algoritma dan struktur data dengan implementasi dalam bahasa C++ dan Java. Mencakup sorting, searching, tree, graph, dan dynamic programming.',
                'description' => 'Buku teks standar untuk mata kuliah Algoritma dan Struktur Data.',
                'total_items' => 5,
                'available_items' => 5,
                'borrowed_items' => 0,
                'price' => 150000,
            ],
            [
                'title' => 'Machine Learning: Theory and Applications',
                'authors' => ['Budi Santoso', 'John Smith'],
                'isbn' => '978-3-662-12345-6',
                'publisher_id' => $publisherSpringer?->id ?? 3,
                'year' => 2023,
                'edition' => 'First Edition',
                'pages' => 380,
                'language' => 'en',
                'classification_id' => $csClassification?->id ?? 1,
                'collection_type_id' => $textbookType?->id ?? 1,
                'gmd_id' => $bookGmd?->id ?? 1,
                'call_number' => '006.3 SAN m',
                'abstract' => 'Comprehensive introduction to machine learning algorithms and their practical applications. Covers supervised learning, unsupervised learning, neural networks, and deep learning.',
                'description' => 'Essential reading for students and practitioners in machine learning.',
                'total_items' => 2,
                'available_items' => 2,
                'borrowed_items' => 0,
                'price' => 750000,
            ],
            [
                'title' => 'Manajemen Pendidikan Modern',
                'authors' => ['H. Abdul Malik Fajar'],
                'isbn' => '978-602-02-2345-7',
                'publisher_id' => $publisherGramedia?->id ?? 1,
                'year' => 2021,
                'edition' => 'Edisi 1',
                'pages' => 320,
                'language' => 'id',
                'classification_id' => $educationClassification?->id ?? 2,
                'collection_type_id' => $textbookType?->id ?? 1,
                'gmd_id' => $bookGmd?->id ?? 1,
                'call_number' => '371.2 FAJ m',
                'abstract' => 'Buku ini membahas konsep dan praktik manajemen pendidikan di era digital. Mencakup perencanaan, pengorganisasian, pengarahan, dan pengawasan lembaga pendidikan.',
                'description' => 'Referensi penting bagi calon pendidik dan manajer sekolah.',
                'total_items' => 4,
                'available_items' => 4,
                'borrowed_items' => 0,
                'price' => 98000,
            ],
            [
                'title' => 'Skripsi: Analisis Performa Sistem Informasi Perpustakaan',
                'authors' => ['Mahasiswa A'],
                'isbn' => '',
                'publisher_id' => $publisherUI?->id ?? 2,
                'year' => 2023,
                'edition' => '',
                'pages' => 120,
                'language' => 'id',
                'classification_id' => $generalClassification?->id ?? 3,
                'collection_type_id' => $thesisType?->id ?? 4,
                'gmd_id' => $bookGmd?->id ?? 1,
                'call_number' => '025.04 MAH s',
                'abstract' => 'Skripsi ini menganalisis performa sistem informasi perpustakaan berbasis web dengan menggunakan metode USE (User Satisfaction Evaluation).',
                'description' => 'Skripsi Program Studi Teknik Informatika.',
                'total_items' => 1,
                'available_items' => 1,
                'borrowed_items' => 0,
                'price' => 0,
            ],
            [
                'title' => 'Kamus Besar Bahasa Indonesia',
                'authors' => ['Tim KBBI Daring'],
                'isbn' => '978-602-03-3456-8',
                'publisher_id' => $publisherGramedia?->id ?? 1,
                'year' => 2020,
                'edition' => 'Edisi 5',
                'pages' => 1800,
                'language' => 'id',
                'classification_id' => $generalClassification?->id ?? 3,
                'collection_type_id' => $referenceType?->id ?? 2,
                'gmd_id' => $bookGmd?->id ?? 1,
                'call_number' => 'R 423 KAM k',
                'abstract' => 'Kamus resmi Bahasa Indonesia yang berisi ribuan entri kata dan istilah beserta definisi dan penggunaannya dalam kalimat.',
                'description' => 'Referensi wajib untuk pengguna Bahasa Indonesia yang baik dan benar.',
                'total_items' => 2,
                'available_items' => 2,
                'borrowed_items' => 0,
                'price' => 250000,
            ],
            [
                'title' => 'Database System Concepts',
                'authors' => ['Abraham Silberschatz', 'Henry F. Korth', 'S. Sudarshan'],
                'isbn' => '978-0-07-802215-9',
                'publisher_id' => $publisherSpringer?->id ?? 3,
                'year' => 2021,
                'edition' => 'Seventh Edition',
                'pages' => 1376,
                'language' => 'en',
                'classification_id' => $csClassification?->id ?? 1,
                'collection_type_id' => $textbookType?->id ?? 1,
                'gmd_id' => $bookGmd?->id ?? 1,
                'call_number' => '005.74 SIL d',
                'abstract' => 'The definitive textbook on database systems, covering relational model, SQL, transaction management, concurrency control, distributed databases, and NoSQL systems.',
                'description' => 'Widely used textbook in database courses worldwide.',
                'total_items' => 3,
                'available_items' => 3,
                'borrowed_items' => 0,
                'price' => 890000,
            ],
            [
                'title' => 'Metodologi Penelitian Pendidikan',
                'authors' => ['Suharsimi Arikunto'],
                'isbn' => '978-979-21-4567-0',
                'publisher_id' => $publisherErlangga?->id ?? 1,
                'year' => 2019,
                'edition' => 'Edisi 7',
                'pages' => 280,
                'language' => 'id',
                'classification_id' => $educationClassification?->id ?? 2,
                'collection_type_id' => $textbookType?->id ?? 1,
                'gmd_id' => $bookGmd?->id ?? 1,
                'call_number' => '370.7 ARI m',
                'abstract' => 'Panduan lengkap metodologi penelitian pendidikan, mencakup penelitian kuantitatif, kualitatif, dan R&D. Dilengkapi contoh proposal dan instrumen penelitian.',
                'description' => 'Buku pegangan utama untuk mahasiswa pendidikan.',
                'total_items' => 6,
                'available_items' => 6,
                'borrowed_items' => 0,
                'price' => 95000,
            ],
            [
                'title' => 'Artificial Intelligence: A Modern Approach',
                'authors' => ['Stuart Russell', 'Peter Norvig'],
                'isbn' => '978-0-13-461099-3',
                'publisher_id' => $publisherSpringer?->id ?? 3,
                'year' => 2022,
                'edition' => 'Fourth Edition',
                'pages' => 1152,
                'language' => 'en',
                'classification_id' => $csClassification?->id ?? 1,
                'collection_type_id' => $textbookType?->id ?? 1,
                'gmd_id' => $bookGmd?->id ?? 1,
                'call_number' => '006.3 RUS a',
                'abstract' => 'Comprehensive textbook covering all aspects of artificial intelligence including search algorithms, knowledge representation, reasoning, planning, machine learning, robotics, and natural language processing.',
                'description' => 'The most widely-used AI textbook in the world.',
                'total_items' => 2,
                'available_items' => 2,
                'borrowed_items' => 0,
                'price' => 1250000,
            ],
            [
                'title' => 'Psikologi Pendidikan',
                'authors' => ['Muhammad Daljono'],
                'isbn' => '978-602-04-5678-1',
                'publisher_id' => $publisherGramedia?->id ?? 1,
                'year' => 2020,
                'edition' => 'Edisi 3',
                'pages' => 340,
                'language' => 'id',
                'classification_id' => $educationClassification?->id ?? 2,
                'collection_type_id' => $textbookType?->id ?? 1,
                'gmd_id' => $bookGmd?->id ?? 1,
                'call_number' => '370.15 DAL p',
                'abstract' => 'Buku ini membahas teori-teori psikologi yang relevan dengan pendidikan, termasuk teori belajar, perkembangan kognitif, motivasi, dan evaluasi.',
                'description' => 'Untuk mahasiswa fakultas keguruan dan ilmu pendidikan.',
                'total_items' => 4,
                'available_items' => 4,
                'borrowed_items' => 0,
                'price' => 125000,
            ],
        ];

        foreach ($collections as $collectionData) {
            $collection = Collection::create($collectionData);

            // Attach subjects
            if ($subjects->isNotEmpty()) {
                $collection->subjects()->attach($subjects->random(rand(1, 3))->pluck('id'));
            }
        }

        $this->command->info('Collections seeded successfully.');
    }
}
