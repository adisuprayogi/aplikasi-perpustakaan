<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core data
        $this->call([
            // Branches must be first as they are referenced by other tables
            BranchSeeder::class,

            // Reference data
            CollectionTypeSeeder::class,
            ClassificationSeeder::class,
            GmdSeeder::class,
            PublisherSeeder::class,
            AuthorSeeder::class,
            SubjectSeeder::class,

            // Permissions and Roles must be before Users
            PermissionSeeder::class,

            // Users (will assign roles based on their role column)
            UserSeeder::class,

            // Members can be seeded after users
            // MemberSeeder::class,

            // Collections and items can be seeded after reference data
            // CollectionSeeder::class,
            // CollectionItemSeeder::class,
        ]);
    }
}
