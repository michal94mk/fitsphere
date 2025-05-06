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
                'email' => 'jan.kowalski@example.com',
                'password' => bcrypt('password'),
                'specialization' => 'Trening siłowy',
                'description' => 'Certyfikowany trener personalny z 8-letnim doświadczeniem.',
                'bio' => 'Specjalista treningu siłowego i budowy masy mięśniowej. Posiadam certyfikaty NASM i NSCA.',
                'experience' => 8,
                'is_approved' => true,
            ],
            [
                'name' => 'Anna Nowak',
                'email' => 'anna.nowak@example.com',
                'password' => bcrypt('password'),
                'specialization' => 'Joga i pilates',
                'description' => 'Instruktorka jogi i pilatesu z certyfikatem międzynarodowym.',
                'bio' => 'Instruktorka jogi z 10-letnim doświadczeniem. Ukończyłam kursy w Indiach i Londynie.',
                'experience' => 10,
                'is_approved' => true,
            ],
            [
                'name' => 'Marek Wiśniewski',
                'email' => 'marek.wisniewski@example.com',
                'password' => bcrypt('password'),
                'specialization' => 'Trening funkcjonalny',
                'description' => 'Specjalista od treningu funkcjonalnego i rehabilitacji sportowej.',
                'bio' => 'Fizjoterapeuta i trener funkcjonalny. Specjalizuję się w FMS i programach korekcyjnych.',
                'experience' => 12,
                'is_approved' => true,
            ],
            [
                'name' => 'Karolina Zielińska',
                'email' => 'karolina.zielinska@example.com',
                'password' => bcrypt('password'),
                'specialization' => 'Trening cardio i HIIT',
                'description' => 'Trenerka specjalizująca się w treningach wysokiej intensywności.',
                'bio' => 'Specjalistka HIIT i treningów interwałowych. Pomagam w transformacjach sylwetki.',
                'experience' => 6,
                'is_approved' => true,
            ],
            [
                'name' => 'Piotr Dąbrowski',
                'email' => 'piotr.dabrowski@example.com',
                'password' => bcrypt('password'),
                'specialization' => 'Dietetyka sportowa',
                'description' => 'Dietetyk sportowy i trener personalny.',
                'bio' => 'Dyplomowany dietetyk i trener. Tworzę spersonalizowane plany żywieniowe i treningowe.',
                'experience' => 9,
                'is_approved' => true,
            ],
            [
                'name' => 'Magda Lewandowska',
                'email' => 'magda.lewandowska@example.com',
                'password' => bcrypt('password'),
                'specialization' => 'Trening kobiet w ciąży',
                'description' => 'Certyfikowana trenerka pre i postnatal.',
                'bio' => 'Specjalistka treningów dla kobiet w ciąży i po porodzie. Certyfikowana trenerka pre i postnatal.',
                'experience' => 7,
                'is_approved' => true,
            ],
            [
                'name' => 'Tomasz Kaczmarek',
                'email' => 'tomasz.kaczmarek@example.com',
                'password' => bcrypt('password'),
                'specialization' => 'Trening seniorów',
                'description' => 'Trener specjalizujący się w aktywności fizycznej dla osób starszych.',
                'bio' => 'Specjalista od treningu dla seniorów. Tworzę programy poprawiające mobilność i siłę u osób starszych.',
                'experience' => 15,
                'is_approved' => true,
            ],
        ];

        foreach ($trainers as $trainerData) {
            // Check if trainer exists to avoid duplicates
            if (!Trainer::where('email', $trainerData['email'])->exists()) {
                Trainer::create($trainerData);
            }
        }
    }
} 