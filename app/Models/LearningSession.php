<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id', 'title', 'type', 'start_time', 'end_time',
        'max_participants', 'url_meeting', 'description', 'price', 'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    // Relasi: Sesi milik satu Mentor
    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    // Relasi: Sesi punya banyak Peserta
    public function participants()
    {
        return $this->hasMany(LearningSessionParticipant::class);
    }
}
