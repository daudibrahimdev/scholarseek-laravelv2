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
        // Panggil UserSeeder di urutan pertama agar ID user konsisten untuk data lainnya
        $this->call([
            UserSeeder::class,                // Akun Admin, Mentor, Mentee
            PackageSeeder::class,             // Data Paket
            ScholarshipCategorySeeder::class, // Kategori Beasiswa
            ScholarshipSeeder::class,         // Data Beasiswa (20 data)
            DocumentCategorySeeder::class,    // Kategori Dokumen
            DocumentSeeder::class,            // Data Dokumen (20 data)
        ]);
    }
}
