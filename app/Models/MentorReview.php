<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorReview extends Model
{
    protected $fillable = 
    ['user_id', 
    'mentor_id', 
    'user_package_id', 
    'rating', 
    'review'];
    public function user()
    {
        // Relasi ke tabel users lewat kolom user_id
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke UserPackage (Paket yang diambil)
    public function userPackage()
    {
        // Relasi ke tabel user_packages lewat kolom user_package_id
        return $this->belongsTo(UserPackage::class, 'user_package_id');
    }
}

