<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quota_sessions',
        'type',
        'duration_days',
    ];

    protected $casts = [
        'price' => 'decimal:2', // Pastikan harga selalu 2 desimal
        'quota_sessions' => 'integer',
        // 'type' tidak perlu di-cast karena sudah enum di DB
    ];
}
