<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'provider',
        'description',
        'start_date',
        'deadline',
        'link_url',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
    ];

    /**
     * Relasi: Beasiswa memiliki banyak Kategori (Many-to-Many).
     */
    public function categories()
    {
        // Menggunakan belongsToMany karena ini relasi Many-to-Many.
        // Laravel secara otomatis menggunakan tabel 'category_scholarship'
        return $this->belongsToMany(ScholarshipCategory::class, 'category_scholarship');
    }
}
