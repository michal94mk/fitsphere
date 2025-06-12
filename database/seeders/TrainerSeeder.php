<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Note: This seeder is now redundant since trainers are created in BaseDataSeeder
     * as User models with role='trainer'. Keeping for backwards compatibility.
     */
    public function run(): void
    {
        $this->command->info('TrainerSeeder: Trainers are now created in BaseDataSeeder as User models with role="trainer".');
        $this->command->info('TrainerSeeder: Checking if trainer users exist...');
        
        $trainerCount = User::where('role', 'trainer')->count();
        
        if ($trainerCount > 0) {
            $this->command->info("TrainerSeeder: Found {$trainerCount} trainer users.");
        } else {
            $this->command->warn('TrainerSeeder: No trainer users found. Make sure BaseDataSeeder runs first.');
        }
    }
} 