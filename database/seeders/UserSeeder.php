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
                'email' => 'admin@example.com',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'name' => 'Kamil Kowalski',
                'email' => 'kamil@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            [
                'name' => 'Anna Nowak',
                'email' => 'anna@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            [
                'name' => 'Piotr Wiśniewski',
                'email' => 'piotr@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            [
                'name' => 'Magda Lewandowska',
                'email' => 'magda@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            [
                'name' => 'Tomasz Dąbrowski',
                'email' => 'tomasz@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            [
                'name' => 'Karolina Zielińska',
                'email' => 'karolina@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            [
                'name' => 'Michał Jankowski',
                'email' => 'michal@example.com',
                'password' => 'password',
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