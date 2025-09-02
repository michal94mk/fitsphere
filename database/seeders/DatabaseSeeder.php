<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting FitSphere database seeding...');

        // Run seeders in proper order due to dependencies
        $this->call([
            BaseDataSeeder::class,      // Users + Categories (foundation)
            TrainerSeeder::class,       // Trainers (depends on base structure)
            ContentSeeder::class,       // Posts (depends on users + categories)
            InteractionSeeder::class,   // Comments (depends on posts + users)
            ImageSeeder::class,         // Images (depends on all content)
        ]);

        $this->command->info('✅ FitSphere database seeding completed successfully!');
    }
}
