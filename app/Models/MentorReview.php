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
}
