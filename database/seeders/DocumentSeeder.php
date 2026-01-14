<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\DocumentCategory;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminId = 3; // Berdasarkan data Admin di SQL lo
        $catIds = DocumentCategory::pluck('id')->toArray();

        $titles = [
            'Template CV ATS Friendly 2026', 'Motivation Letter Lolos Oxford', 
            'Essay Kontribusi untuk Indonesia', 'Panduan IELTS Writing Task 2',
            'Checklist Dokumen Beasiswa Erasmus', 'Contoh Personal Statement GKS',
            'Template Portofolio Desain', 'Panduan Sertifikasi MEXT Japan',
            'Cara Menghubungi Profesor Luar Negeri', 'Review Essay Beasiswa Fulbright'
            // Tambahin sendiri sampe 20 biar cepet bro
        ];

        foreach ($titles as $title) {
            Document::create([
                'title' => $title,
                'description' => 'Persyaratan lengkap beasiswa untuk ' . $title,
                'file_path' => 'documents/sample.pdf',
                'document_category_id' => $catIds[array_rand($catIds)],
                'uploaded_by' => $adminId
            ]);
        }
    }
}
