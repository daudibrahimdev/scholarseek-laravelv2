<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data paket sesuai dengan model bisnis ScholarSeek (Skema A)
        $packages = [
            [
                'name' => 'Group Masterclass',
                'description' => 'Akses penuh ke semua sesi Group Webinar/Masterclass bulanan.',
                'price' => 500000.00,
                'quota_sessions' => 0, // Kuota 0 berarti akses tak terbatas untuk tipe Group
                'type' => 'group',
                'duration_days' => 30, // Berlaku 30 hari
            ],
            [
                'name' => 'Private Class',
                'description' => 'Bimbingan 1-on-1 intensif (5 sesi) dengan mentor pilihan untuk strategi awal beasiswa.',
                'price' => 1500000.00,
                'quota_sessions' => 5, // 5 Sesi Private
                'type' => 'private',
                'duration_days' => 60, // Berlaku 60 hari
            ],
            [
                'name' => 'Breakthrough Package',
                'description' => 'Paket komprehensif, mencakup 10 sesi private dan akses Masterclass selama 3 bulan.',
                'price' => 3000000.00,
                'quota_sessions' => 10, // 10 Sesi Private
                'type' => 'hybrid', // Bisa Group dan Private
                'duration_days' => 90, // Berlaku 90 hari
            ],
            [
                'name' => 'Ultimate Package',
                'description' => 'Pendampingan penuh hingga mendapatkan LoA. Total 30 sesi private dan akses Group tak terbatas.',
                'price' => 6500000.00,
                'quota_sessions' => 30, // 30 Sesi Private
                'type' => 'hybrid',
                'duration_days' => 180, // Berlaku 180 hari
            ],
        ];

        foreach ($packages as $package) {
            Package::firstOrCreate(['name' => $package['name']], $package);
        }
    }
}
