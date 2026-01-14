<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ScholarshipCategory;

class ScholarshipCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['name' => 'S1', 'slug' => 's1'],
            ['name' => 'S2', 'slug' => 's2'],
            ['name' => 'S3', 'slug' => 's3'],
            ['name' => 'FULLY FUNDED', 'slug' => 'fully-funded'],
            ['name' => 'Dalam Negeri', 'slug' => 'dalam-negeri'],
            ['name' => 'Internasional', 'slug' => 'internasional'],
        ];

        foreach ($categories as $cat) {
            ScholarshipCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
