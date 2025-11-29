<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    /**
     * Relasi: Kategori dimiliki oleh banyak Beasiswa (Many-to-Many).
     */
    public function scholarships()
    {
        // Menggunakan belongsToMany karena ini relasi Many-to-Many.
        // Laravel secara otomatis menggunakan tabel 'category_scholarship'
        return $this->belongsToMany(Scholarship::class, 'category_scholarship');
    }
}
