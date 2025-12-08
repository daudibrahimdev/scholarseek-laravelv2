<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Memanggil PackageSeeder untuk menambahkan data paket awal
        $this->call([
            // UserSeeder::class, // Buat nanti jika perlu
            // MentorSeeder::class, // Buat nanti jika perlu
            PackageSeeder::class, //from database/seeders/PackageSeeder.php
        ]);
    }
}
