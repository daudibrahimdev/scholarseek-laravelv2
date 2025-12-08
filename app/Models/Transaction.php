<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'mentor_id',
        'amount',
        'type', // 'purchase', 'withdrawal'
        'status',
        'payment_method',
        'reference_id',
        'description',
    ];

    // Relasi ke User (Mentee/Admin yang memproses)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke Paket
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    
    // Relasi ke Mentor (kalo ini withdrawal)
    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}
