<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['username' => env('SIRAPIK_SUPERADMIN_USERNAME','superadmin'), 'email' => env('SIRAPIK_SUPERADMIN_EMAIL','superadmin@sirapik.local'), 'name' => 'Super Administrator SIRAPIK', 'role' => 'super_admin', 'password' => env('SIRAPIK_SUPERADMIN_PASSWORD')],
            ['username' => env('SIRAPIK_ADMIN_USERNAME','admin'), 'email' => env('SIRAPIK_ADMIN_EMAIL','admin@sirapik.local'), 'name' => 'Administrator SIRAPIK', 'role' => 'admin', 'password' => env('SIRAPIK_ADMIN_PASSWORD')],
            ['username' => env('SIRAPIK_PIMPINAN_USERNAME','pimpinan'), 'email' => env('SIRAPIK_PIMPINAN_EMAIL','pimpinan@sirapik.local'), 'name' => 'Pimpinan SIRAPIK', 'role' => 'pimpinan', 'password' => env('SIRAPIK_PIMPINAN_PASSWORD')],
        ];
        foreach ($accounts as $account) {
            // Jangan membuat akun dengan password default yang diketahui publik. Atur variabel SIRAPIK_*_PASSWORD di .env.
            if (empty($account['password'])) continue;
            User::updateOrCreate(['username' => $account['username']], [
                'name' => $account['name'], 'email' => $account['email'], 'role' => $account['role'],
                'password' => Hash::make($account['password']),
            ]);
        }
    }
}