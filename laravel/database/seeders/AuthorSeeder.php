<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            // Indonesian Authors
            ['name' => 'Ki Hadjar Dewantara', 'type' => 'personal'],
            ['name' => 'Pramoedya Ananta Toer', 'type' => 'personal'],
            ['name' => 'Chairil Anwar', 'type' => 'personal'],
            ['name' => 'W.S. Rendra', 'type' => 'personal'],
            ['name' => 'Ahmad Dahlan', 'type' => 'personal'],
            ['name' => 'Hamka', 'type' => 'personal'],
            ['name' => 'Taufik Ismail', 'type' => 'personal'],
            ['name' => 'Goenawan Mohamad', 'type' => 'personal'],
            ['name' => 'Umar Kayam', 'type' => 'personal'],
            ['name' => 'Putu Wijaya', 'type' => 'personal'],
            ['name' => 'Budi Darma', 'type' => 'personal'],
            ['name' => 'Iwan Simatupang', 'type' => 'personal'],
            ['name' => 'Mochtar Lubis', 'type' => 'personal'],
            ['name' => 'Mangkunegara IV', 'type' => 'personal'],
            ['name' => 'Raden Adjeng Kartini', 'type' => 'personal'],
            // International Authors
            ['name' => 'William Shakespeare', 'type' => 'personal'],
            ['name' => 'Jane Austen', 'type' => 'personal'],
            ['name' => 'Charles Dickens', 'type' => 'personal'],
            ['name' => 'Leo Tolstoy', 'type' => 'personal'],
            ['name' => 'Fyodor Dostoevsky', 'type' => 'personal'],
            ['name' => 'Mark Twain', 'type' => 'personal'],
            ['name' => 'Ernest Hemingway', 'type' => 'personal'],
            ['name' => 'George Orwell', 'type' => 'personal'],
            ['name' => 'Gabriel Garcia Marquez', 'type' => 'personal'],
            ['name' => 'J.K. Rowling', 'type' => 'personal'],
            ['name' => 'Stephen King', 'type' => 'personal'],
            ['name' => 'Dan Brown', 'type' => 'personal'],
            ['name' => 'Haruki Murakami', 'type' => 'personal'],
            // Corporate/Institutional Authors
            ['name' => 'Badan Pengembangan dan Pembinaan Bahasa', 'type' => 'corporate'],
            ['name' => 'Pusat Kurikulum dan Perbukuan', 'type' => 'corporate'],
            ['name' => 'Kementerian Pendidikan dan Kebudayaan', 'type' => 'corporate'],
            ['name' => 'Badan Pusat Statistik', 'type' => 'corporate'],
            ['name' => 'Lembaga Ilmu Pengetahuan Indonesia', 'type' => 'corporate'],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }

        $this->command->info('Authors seeded successfully.');
    }
}
