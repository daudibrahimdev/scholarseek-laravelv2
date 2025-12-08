<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningSessionParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'learning_session_id', 'mentee_id', 'transaction_id', 'status', 'notes'
    ];

    // Relasi: Pendaftaran milik satu Sesi
    public function session()
    {
        return $this->belongsTo(LearningSession::class, 'learning_session_id');
    }

    // Relasi: Pendaftaran milik satu Mentee (User)
    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
