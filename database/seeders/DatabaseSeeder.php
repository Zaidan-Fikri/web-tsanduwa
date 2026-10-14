<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ContentSeeder::class,
        ]);

        // Pindahkan gambar contoh (file public/img & link Unsplash) ke database dalam bentuk terkompres.
        $this->command->call('media:import');
    }
}
