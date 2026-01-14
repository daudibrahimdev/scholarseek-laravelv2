<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Scholarship;
use App\Models\ScholarshipCategory;
use Illuminate\Support\Str;

class ScholarshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $catIds = ScholarshipCategory::pluck('id')->toArray();

        $data = [
            ['LPDP 2026', 'Kemenkeu RI'], ['MEXT 2026', 'Pemerintah Jepang'],
            ['Fulbright Master', 'AMINEF'], ['Chevening UK', 'British Council'],
            ['AAS Australia', 'Pemerintah Australia'], ['GKS Korea', 'NIIED']
            // Tambahin sendiri sampe 20
        ];

        foreach ($data as $item) {
            $scholarship = Scholarship::create([
                'title' => $item[0],
                'provider' => $item[1],
                'description' => 'Deskripsi beasiswa ' . $item[0],
                'deadline' => now()->addMonths(6),
                'link_url' => 'https://scholarseek.id/' . Str::slug($item[0])
            ]);

            // Ambil 2 kategori acak buat relasi pivot
            $scholarship->categories()->attach(array_rand(array_flip($catIds), 2));
        }
    }
}
