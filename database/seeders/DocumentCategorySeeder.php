<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DocumentCategory;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            'Template CV & Resume', 'Motivation Letter', 'Syllabus & Course Map',
            'Contoh Essay LPDP', 'Panduan Paspor & Visa', 'Persyaratan TOEFL/IELTS'
        ];

        foreach ($categories as $cat) {
            DocumentCategory::updateOrCreate(['name' => $cat]);
        }
    }
}
