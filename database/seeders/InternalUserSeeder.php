<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class InternalUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'username' => 'superadmin@sirapik.local',
                'email' => 'superadmin@sirapik.local',
                'password' => 'superadmin123',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Admin',
                'username' => 'admin@sirapik.local',
                'email' => 'admin@sirapik.local',
                'password' => 'admin123',
                'role' => 'admin',
            ],
            [
                'name' => 'Pimpinan',
                'username' => 'pimpinan@sirapik.local',
                'email' => 'pimpinan@sirapik.local',
                'password' => 'pimpinan123',
                'role' => 'pimpinan',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'password' => Hash::make($userData['password']),
                    'role' => $userData['role'],
                ]
            );
        }
    }
}