<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publishers = [
            ['name' => 'Erlangga', 'city' => 'Jakarta', 'country' => 'Indonesia'],
            ['name' => 'Gramedia Pustaka Utama', 'city' => 'Jakarta', 'country' => 'Indonesia'],
            ['name' => 'Penerbit Universitas Indonesia', 'city' => 'Depok', 'country' => 'Indonesia'],
            ['name' => 'Prenada Media', 'city' => 'Jakarta', 'country' => 'Indonesia'],
            ['name' => 'Raja Grafindo Persada', 'city' => 'Jakarta', 'country' => 'Indonesia'],
            ['name' => 'Salemba Empat', 'city' => 'Jakarta', 'country' => 'Indonesia'],
            ['name' => 'Bumi Aksara', 'city' => 'Jakarta', 'country' => 'Indonesia'],
            ['name' => 'PT Remaja Rosdakarya', 'city' => 'Bandung', 'country' => 'Indonesia'],
            ['name' => 'Pustaka Pelajar', 'city' => 'Yogyakarta', 'country' => 'Indonesia'],
            ['name' => 'Andi Offset', 'city' => 'Yogyakarta', 'country' => 'Indonesia'],
            ['name' => 'Gadjah Mada University Press', 'city' => 'Yogyakarta', 'country' => 'Indonesia'],
            ['name' => 'Pearson Education', 'city' => 'Boston', 'country' => 'USA'],
            ['name' => 'McGraw-Hill Education', 'city' => 'New York', 'country' => 'USA'],
            ['name' => 'Springer', 'city' => 'Berlin', 'country' => 'Germany'],
            ['name' => 'Elsevier', 'city' => 'Amsterdam', 'country' => 'Netherlands'],
            ['name' => 'Cambridge University Press', 'city' => 'Cambridge', 'country' => 'UK'],
            ['name' => 'Oxford University Press', 'city' => 'Oxford', 'country' => 'UK'],
            ['name' => 'Wiley', 'city' => 'Hoboken', 'country' => 'USA'],
            ['name' => 'Routledge', 'city' => 'London', 'country' => 'UK'],
            ['name' => 'Taylor & Francis', 'city' => 'London', 'country' => 'UK'],
        ];

        foreach ($publishers as $publisher) {
            Publisher::create($publisher);
        }

        $this->command->info('Publishers seeded successfully.');
    }
}
