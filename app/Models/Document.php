<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',
        'file_path',
        'description',
        'document_category_id',
        'uploaded_by',
    ];

    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
