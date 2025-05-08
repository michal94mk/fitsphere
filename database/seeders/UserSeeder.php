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
            ],
            [
                'name' => 'Kamil Kowalski',
                'email' => 'kamil.kowalski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
            ],
            [
                'name' => 'Anna Nowak',
                'email' => 'anna.nowak@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
            ],
            [
                'name' => 'Piotr Wiśniewski',
                'email' => 'piotr.wisniewski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
            ],
            [
                'name' => 'Magda Lewandowska',
                'email' => 'magda.lewandowska@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
            ],
            [
                'name' => 'Tomasz Dąbrowski',
                'email' => 'tomasz.dabrowski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
            ],
            [
                'name' => 'Karolina Zielińska',
                'email' => 'karolina.zielinska@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
            ],
            [
                'name' => 'Michał Jankowski',
                'email' => 'michal.jankowski@fitsphere.com',
                'password' => 'Password123',
                'role' => 'user',
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
                ]);
            }
        }
    }
} 