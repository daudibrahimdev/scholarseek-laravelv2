<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPackage extends Model
{
    use HasFactory;

    protected $table = 'user_packages';

    protected $fillable = [
        'user_id',
        'package_id',
        'mentor_id',
        'initial_quota',
        'remaining_quota',
        'purchased_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
        'initial_quota' => 'integer',
        'remaining_quota' => 'integer',
    ];

    // Relasi ke User (Mentee)
    public function mentee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Paket
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
    
    // Relasi ke Mentor yang di-assign (PENTING!)
    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }
}
