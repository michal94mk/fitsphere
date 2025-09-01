<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Support\Facades\Hash;

class BaseDataSeeder extends Seeder
{
    /**
     * Seed basic application data (users and categories).
     */
    public function run(): void
    {
        $this->seedUsers();
        $this->seedCategories();
    }

    private function seedUsers(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@fitsphere.com',
                'password' => 'Password123',
                'role' => 'admin,user',
                'image' => null,
            ],
            [
                'name' => 'Kamil Kowalski',
                'email' => 'kamil.kowalski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => 'images/trainers/EsSCqJZKRtsZpdl1NF154SWxGsX5Yjw5UjJrlQjM.jpg',
            ],
            [
                'name' => 'Anna Nowak',
                'email' => 'anna.nowak@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => 'images/trainers/oVNdp5Q6AtoxrVxhP8BSwaNtD7MNeIolPg7GDT1F.jpg',
            ],
            [
                'name' => 'Piotr Wiśniewski',
                'email' => 'piotr.wisniewski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => 'images/trainers/r0GFOFudM3A6yBY5kYF89WGXLWScxmDvKC4l8rG0.jpg',
            ],
            [
                'name' => 'Magda Lewandowska',
                'email' => 'magda.lewandowska@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => null,
            ],
            [
                'name' => 'Tomasz Dąbrowski',
                'email' => 'tomasz.dabrowski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => null,
            ],
            [
                'name' => 'Karolina Zielińska',
                'email' => 'karolina.zielinska@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => null,
            ],
            [
                'name' => 'Michał Jankowski',
                'email' => 'michal.jankowski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => null,
            ],
            // Trainers
            [
                'name' => 'Jan Kowalski',
                'email' => 'jan.kowalski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'trainer',
                'specialization' => 'Trening siłowy',
                'is_approved' => true,
                'image' => 'images/trainers/trainer1.jpg',
            ],
            [
                'name' => 'Anna Nowakówna',
                'email' => 'anna.nowakówna@fitsphere.com',
                'password' => 'Password123',
                'role' => 'trainer',
                'specialization' => 'Joga i pilates',
                'is_approved' => true,
                'image' => 'images/trainers/trainer2.jpg',
            ],
            [
                'name' => 'Marek Wiśniewski',
                'email' => 'marek.wisniewski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'trainer',
                'specialization' => 'Trening funkcjonalny',
                'is_approved' => true,
                'image' => 'images/trainers/trainer3.jpg',
            ],
            [
                'name' => 'Katarzyna Włodarczyk',
                'email' => 'katarzyna.wlodarczyk@fitsphere.com',
                'password' => 'Password123',
                'role' => 'trainer',
                'specialization' => 'Fitness i cardio',
                'is_approved' => true,
                'image' => 'images/trainers/trainer4.jpg',
            ],
            [
                'name' => 'Robert Mazur',
                'email' => 'robert.mazur@fitsphere.com',
                'password' => 'Password123',
                'role' => 'trainer',
                'specialization' => 'CrossFit i sporty walki',
                'is_approved' => true,
                'image' => 'images/trainers/trainer5.jpg',
            ],
        ];

        foreach ($users as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'email_verified_at' => now(),
                    'password' => bcrypt($userData['password']),
                    'role' => $userData['role'],
                    'specialization' => $userData['specialization'] ?? null,
                    'is_approved' => $userData['is_approved'] ?? true, // Default true for all users
                    'image' => $userData['image'],
                ]);
            }
        }
    }

    private function seedCategories(): void
    {
        $categories = [
            [
                'name' => 'Trening siłowy',
                'translations' => [
                    'en' => ['name' => 'Strength Training'],
                    'pl' => ['name' => 'Trening siłowy']
                ]
            ],
            [
                'name' => 'Cardio',
                'translations' => [
                    'en' => ['name' => 'Cardio'],
                    'pl' => ['name' => 'Cardio']
                ]
            ],
            [
                'name' => 'Dieta i odżywianie',
                'translations' => [
                    'en' => ['name' => 'Diet and Nutrition'],
                    'pl' => ['name' => 'Dieta i odżywianie']
                ]
            ],
            [
                'name' => 'Zdrowy styl życia',
                'translations' => [
                    'en' => ['name' => 'Healthy Lifestyle'],
                    'pl' => ['name' => 'Zdrowy styl życia']
                ]
            ],
            [
                'name' => 'Motywacja',
                'translations' => [
                    'en' => ['name' => 'Motivation'],
                    'pl' => ['name' => 'Motywacja']
                ]
            ],
            [
                'name' => 'Suplementacja',
                'translations' => [
                    'en' => ['name' => 'Supplementation'],
                    'pl' => ['name' => 'Suplementacja']
                ]
            ],
            [
                'name' => 'Trening funkcjonalny',
                'translations' => [
                    'en' => ['name' => 'Functional Training'],
                    'pl' => ['name' => 'Trening funkcjonalny']
                ]
            ],
            [
                'name' => 'Joga i rozciąganie',
                'translations' => [
                    'en' => ['name' => 'Yoga and Stretching'],
                    'pl' => ['name' => 'Joga i rozciąganie']
                ]
            ],
            [
                'name' => 'Plany treningowe',
                'translations' => [
                    'en' => ['name' => 'Training Plans'],
                    'pl' => ['name' => 'Plany treningowe']
                ]
            ],
            [
                'name' => 'Regeneracja',
                'translations' => [
                    'en' => ['name' => 'Recovery'],
                    'pl' => ['name' => 'Regeneracja']
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $translations = $categoryData['translations'] ?? [];
            unset($categoryData['translations']);
            
            $category = Category::firstOrCreate(['name' => $categoryData['name']]);
            
            // Add translations
            foreach ($translations as $locale => $translationData) {
                CategoryTranslation::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'locale' => $locale
                    ],
                    [
                        'name' => $translationData['name']
                    ]
                );
            }
        }
    }
} 