<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trainer;
use App\Models\TrainerTranslation;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainers = [
            [
                'name' => 'Jan Kowalski',
                'email' => 'jan.kowalski@fitsphere.com',
                'password' => 'Password123',
                'specialization' => 'Trening siłowy',
                'description' => 'Certyfikowany trener personalny z 8-letnim doświadczeniem.',
                'bio' => 'Specjalista treningu siłowego i budowy masy mięśniowej. Posiadam certyfikaty NASM i NSCA.',
                'experience' => 8,
                'is_approved' => true,
                'specialties' => 'Siła, Przyrost mięśni, Odżywianie',
                'image' => 'images/trainers/trainer1.jpg',
                'translations' => [
                    'en' => [
                        'specialization' => 'Strength Training',
                        'description' => 'Certified personal trainer with 8 years of experience.',
                        'bio' => 'Strength training and muscle building specialist. I hold NASM and NSCA certifications.',
                        'specialties' => 'Strength, Muscle gain, Nutrition'
                    ],
                    'pl' => [
                        'specialization' => 'Trening siłowy',
                        'description' => 'Certyfikowany trener personalny z 8-letnim doświadczeniem.',
                        'bio' => 'Specjalista treningu siłowego i budowy masy mięśniowej. Posiadam certyfikaty NASM i NSCA.',
                        'specialties' => 'Siła, Przyrost mięśni, Odżywianie'
                    ]
                ]
            ],
            [
                'name' => 'Anna Nowak',
                'email' => 'anna.nowak@fitsphere.com',
                'password' => 'Password123',
                'specialization' => 'Joga i pilates',
                'description' => 'Instruktorka jogi i pilatesu z certyfikatem międzynarodowym.',
                'bio' => 'Instruktorka jogi z 10-letnim doświadczeniem. Ukończyłam kursy w Indiach i Londynie.',
                'experience' => 10,
                'is_approved' => true,
                'specialties' => 'Joga, Pilates, Elastyczność, Medytacja',
                'image' => 'images/trainers/trainer2.jpg',
                'translations' => [
                    'en' => [
                        'specialization' => 'Yoga and Pilates',
                        'description' => 'Yoga and Pilates instructor with international certification.',
                        'bio' => 'Yoga instructor with 10 years of experience. I completed courses in India and London.',
                        'specialties' => 'Yoga, Pilates, Flexibility, Meditation'
                    ],
                    'pl' => [
                        'specialization' => 'Joga i pilates',
                        'description' => 'Instruktorka jogi i pilatesu z certyfikatem międzynarodowym.',
                        'bio' => 'Instruktorka jogi z 10-letnim doświadczeniem. Ukończyłam kursy w Indiach i Londynie.',
                        'specialties' => 'Joga, Pilates, Elastyczność, Medytacja'
                    ]
                ]
            ],
            [
                'name' => 'Marek Wiśniewski',
                'email' => 'marek.wisniewski@fitsphere.com',
                'password' => 'Password123',
                'specialization' => 'Trening funkcjonalny',
                'description' => 'Specjalista od treningu funkcjonalnego i rehabilitacji sportowej.',
                'bio' => 'Fizjoterapeuta i trener funkcjonalny. Specjalizuję się w FMS i programach korekcyjnych.',
                'experience' => 12,
                'is_approved' => true,
                'specialties' => 'Trening funkcjonalny, Rehabilitacja, Ćwiczenia korekcyjne',
                'image' => 'images/trainers/trainer3.jpg',
                'translations' => [
                    'en' => [
                        'specialization' => 'Functional Training',
                        'description' => 'Functional training and sports rehabilitation specialist.',
                        'bio' => 'Physiotherapist and functional trainer. I specialize in FMS and corrective exercise programs.',
                        'specialties' => 'Functional training, Rehabilitation, Corrective exercise'
                    ],
                    'pl' => [
                        'specialization' => 'Trening funkcjonalny',
                        'description' => 'Specjalista od treningu funkcjonalnego i rehabilitacji sportowej.',
                        'bio' => 'Fizjoterapeuta i trener funkcjonalny. Specjalizuję się w FMS i programach korekcyjnych.',
                        'specialties' => 'Trening funkcjonalny, Rehabilitacja, Ćwiczenia korekcyjne'
                    ]
                ]
            ],
        ];

        foreach ($trainers as $trainerData) {
            $translations = $trainerData['translations'] ?? [];
            unset($trainerData['translations']);
            
            // Check if trainer exists to avoid duplicates
            if (!Trainer::where('email', $trainerData['email'])->exists()) {
                // Encrypt password before saving
                $trainerData['password'] = bcrypt($trainerData['password']);
                $trainer = Trainer::create($trainerData);
                
                // Add translations
                foreach ($translations as $locale => $translationData) {
                    TrainerTranslation::create([
                        'trainer_id' => $trainer->id,
                        'locale' => $locale,
                        'specialization' => $translationData['specialization'],
                        'description' => $translationData['description'],
                        'bio' => $translationData['bio'],
                        'specialties' => $translationData['specialties'] ?? null
                    ]);
                }
            }
        }
    }
} 