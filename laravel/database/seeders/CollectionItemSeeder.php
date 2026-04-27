<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Collection;
use App\Models\CollectionItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collections = Collection::all();
        $mainBranch = Branch::first();

        if ($collections->isEmpty()) {
            $this->command->warn('No collections found. Please run CollectionSeeder first.');
            return;
        }

        $itemConditions = ['good', 'fair', 'poor'];
        $itemSources = ['purchase', 'donation', 'grant'];
        $itemLocations = ['Rak A-01', 'Rak A-02', 'Rak B-01', 'Rak B-02', 'Rak C-01', 'Referensi', 'Skripsi'];

        foreach ($collections as $collection) {
            // Create items based on total_items count
            for ($i = 1; $i <= $collection->total_items; $i++) {
                $barcode = $this->generateBarcode($collection->id, $i);

                CollectionItem::create([
                    'collection_id' => $collection->id,
                    'barcode' => $barcode,
                    'call_number' => $collection->call_number . '.' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'branch_id' => $mainBranch?->id ?? 1,
                    'location' => $itemLocations[array_rand($itemLocations)],
                    'status' => 'available',
                    'condition' => $itemConditions[array_rand($itemConditions)],
                    'acquired_date' => now()->subDays(rand(30, 365))->format('Y-m-d'),
                    'acquired_price' => $collection->price * (1 + (rand(-10, 10) / 100)), // +/- 10% variation
                    'source' => $itemSources[array_rand($itemSources)],
                ]);
            }
        }

        $this->command->info('Collection items seeded successfully.');
    }

    /**
     * Generate unique barcode for item.
     */
    private function generateBarcode(int $collectionId, int $sequence): string
    {
        // Format: COLL-ID-SEQ (e.g., 000001-01, 000001-02)
        return sprintf('%06d-%02d', $collectionId, $sequence);
    }
}
