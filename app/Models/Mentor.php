<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory; 

    protected $fillable = [
        'user_id', // fix mass assignment exception
        'bio',
        'expertise_areas', // Kolom JSON
        'profile_picture',
        'domicile_city',
        'full_address',
        'phone_number',
        'cv_path',
        'motivation_letter_path', // Path file
        'verification_status',
        'is_available',
        'avg_rating',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'avg_rating' => 'float',
        'expertise_areas' => 'array', // Untuk konversi JSON ke Array PHP
    ];

    // kalo sewaktu waktu butuh timestamps, tinggal di uncomment
    // public $timestamps = true; 
    
    /**
     * Relasi: Mentor dimiliki oleh satu User (One-to-One Inverse).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}