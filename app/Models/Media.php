<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'video_id',
        'file_path',
        'encoded_path',
        'media_status',
    ];

    protected $casts = [
        'id' => 'string',
        'video_id' => 'string',
    ];

    public $incrementing = false;
}
