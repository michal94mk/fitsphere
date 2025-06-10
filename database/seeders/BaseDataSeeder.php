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
                'role' => 'admin',
                'image' => 'images/users/user8.jpg',
            ],
            [
                'name' => 'Kamil Kowalski',
                'email' => 'kamil.kowalski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => 'images/users/user1.jpg',
            ],
            [
                'name' => 'Anna Nowak',
                'email' => 'anna.nowak@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => 'images/users/user2.jpg',
            ],
            [
                'name' => 'Piotr Wiśniewski',
                'email' => 'piotr.wisniewski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => 'images/users/user9.png',
            ],
            [
                'name' => 'Magda Lewandowska',
                'email' => 'magda.lewandowska@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => 'images/users/user4.jpg',
            ],
            [
                'name' => 'Tomasz Dąbrowski',
                'email' => 'tomasz.dabrowski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => 'images/users/user5.jpg',
            ],
            [
                'name' => 'Karolina Zielińska',
                'email' => 'karolina.zielinska@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => 'images/users/user6.jpg',
            ],
            [
                'name' => 'Michał Jankowski',
                'email' => 'michal.jankowski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
                'image' => 'images/users/user7.jpg',
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