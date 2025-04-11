<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    /**
     * Dodaje przykładowych trenerów do bazy danych.
     */
    public function run(): void
    {
        $trainers = [
            [
                'name' => 'Jan Kowalski',
                'email' => 'jan.kowalski@example.com',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'specialization' => 'Trener personalny',
                'description' => 'Jan to doświadczony trener personalny z ponad 10-letnim doświadczeniem. Specjalizuje się w treningu siłowym i redukcji wagi.',
                'image' => null, // Brak zdjęcia
                'bio' => "Jan Kowalski to ekspert w dziedzinie treningu siłowego i odchudzania. Pomógł już ponad 100 klientom osiągnąć ich cele fitness.\n\nPosiada licencję trenera personalnego oraz dyplom z fizjoterapii. W swojej pracy skupia się na indywidualnym podejściu do każdego klienta.",
                'specialties' => 'Trening siłowy, Redukcja wagi, Dieta',
                'rating' => 5
            ],
            [
                'name' => 'Anna Nowak',
                'email' => 'anna.nowak@example.com',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'specialization' => 'Trener jogi',
                'description' => 'Anna to certyfikowana instruktorka jogi z pasją do nauczania. Prowadzi zajęcia jogi dla wszystkich poziomów zaawansowania.',
                'image' => null, // Brak zdjęcia
                'bio' => "Anna Nowak praktykuje jogę od ponad 15 lat. Ukończyła liczne szkolenia i warsztaty w Indiach i Europie.\n\nJej zajęcia to połączenie tradycyjnej hatha jogi z elementami jogi dynamicznej. Skupia się na poprawnej technice i świadomym oddychaniu.",
                'specialties' => 'Hatha joga, Vinyasa, Joga dla początkujących, Joga terapeutyczna',
                'rating' => 4
            ]
        ];

        foreach ($trainers as $trainerData) {
            // Sprawdzamy, czy trener o tym emailu już istnieje
            $existingTrainer = User::where('email', $trainerData['email'])->first();
            
            if (!$existingTrainer) {
                User::create($trainerData);
            } else {
                // Aktualizujemy istniejącego użytkownika, aby miał rolę 'trainer'
                $existingTrainer->update($trainerData);
            }
        }
    }
} 