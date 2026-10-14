<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun awal. Segera ganti password setelah login pertama (menu Akun Saya).
        // firstOrCreate: menjalankan seeder ulang tidak akan mereset password yang sudah diganti.
        User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin',
                'email' => null,
                'role' => 'superadmin',
                'password' => 'superadmin123',
            ],
        );
    }
}
