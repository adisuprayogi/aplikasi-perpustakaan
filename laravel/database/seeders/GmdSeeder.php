<?php

namespace Database\Seeders;

use App\Models\Gmd;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GmdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gmds = [
            ['code' => 'BK', 'name' => 'Buku Teks', 'name_en' => 'Book'],
            ['code' => 'TXT', 'name' => 'Teks', 'name_en' => 'Text'],
            ['code' => 'JN', 'name' => 'Jurnal', 'name_en' => 'Journal'],
            ['code' => 'MGZ', 'name' => 'Majalah', 'name_en' => 'Magazine'],
            ['code' => 'NP', 'name' => 'Naskah', 'name_en' => 'Manuscript'],
            ['code' => 'CDR', 'name' => 'CD-ROM', 'name_en' => 'CD-ROM'],
            ['code' => 'DVD', 'name' => 'DVD', 'name_en' => 'DVD'],
            ['code' => 'VCD', 'name' => 'VCD', 'name_en' => 'VCD'],
            ['code' => 'BLR', 'name' => 'Blu-ray', 'name_en' => 'Blu-ray'],
            ['code' => 'VHS', 'name' => 'Videokaset', 'name_en' => 'Videocassette'],
            ['code' => 'AUD', 'name' => 'Audio Kaset', 'name_en' => 'Audio Cassette'],
            ['code' => 'CDA', 'name' => 'Audio CD', 'name_en' => 'Audio CD'],
            ['code' => 'MP3', 'name' => 'File Audio', 'name_en' => 'Audio File'],
            ['code' => 'MAP', 'name' => 'Peta', 'name_en' => 'Map'],
            ['code' => 'GLO', 'name' => 'Globe', 'name_en' => 'Globe'],
            ['code' => 'CHT', 'name' => 'Bagan/Chart', 'name_en' => 'Chart'],
            ['code' => 'ART', 'name' => 'Karya Seni', 'name_en' => 'Art Work'],
            ['code' => 'PHO', 'name' => 'Foto', 'name_en' => 'Photograph'],
            ['code' => 'SLD', 'name' => 'Slide', 'name_en' => 'Slide'],
            ['code' => 'FLM', 'name' => 'Film', 'name_en' => 'Film'],
            ['code' => 'MOD', 'name' => 'Model', 'name_en' => 'Model'],
            ['code' => 'KIT', 'name' => 'Kit', 'name_en' => 'Kit'],
            ['code' => 'MIC', 'name' => 'Mikrofilm', 'name_en' => 'Microfilm'],
            ['code' => 'COM', 'name' => 'File Komputer', 'name_en' => 'Computer File'],
            ['code' => 'EBK', 'name' => 'E-Book', 'name_en' => 'E-Book'],
            ['code' => 'RES', 'name' => 'Sumber Daya Internet', 'name_en' => 'Internet Resource'],
            ['code' => 'OTH', 'name' => 'Lainnya', 'name_en' => 'Other'],
        ];

        foreach ($gmds as $gmd) {
            Gmd::create($gmd);
        }

        $this->command->info('GMDs seeded successfully.');
    }
}
