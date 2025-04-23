<?php

namespace App\Console\Commands;

use App\Models\Trainer;
use App\Models\TrainerTranslation;
use Illuminate\Console\Command;

class PopulateTrainerTranslationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trainers:populate-translations {locale=pl : The locale to populate translations for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate trainer translations from existing trainer data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $locale = $this->argument('locale');
        $this->info("Populating trainer translations for locale: {$locale}");

        $trainers = Trainer::all();
        $this->info("Found {$trainers->count()} trainers");

        $created = 0;
        $skipped = 0;

        foreach ($trainers as $trainer) {
            // Check if translation already exists
            $exists = $trainer->translations()
                ->where('locale', $locale)
                ->exists();

            if ($exists) {
                $this->line("Translation for trainer {$trainer->name} already exists, skipping");
                $skipped++;
                continue;
            }

            // Create new translation
            TrainerTranslation::create([
                'trainer_id' => $trainer->id,
                'locale' => $locale,
                'specialization' => $trainer->specialization,
                'description' => $trainer->description,
                'bio' => $trainer->bio,
                'specialties' => $trainer->specialties,
            ]);

            $this->line("Created translation for trainer {$trainer->name}");
            $created++;
        }

        $this->info("Completed! Created {$created} new translations, skipped {$skipped} existing translations");
        
        return 0;
    }
}
