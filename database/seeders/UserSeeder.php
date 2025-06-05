<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
                'image' => 'images/users/user3.jpg',
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
} 