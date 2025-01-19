<?php

namespace App\Models;

use App\Models\Traits\UuidTrait;
use core\domain\enum\MediaStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, UuidTrait;

    protected $table = 'video_medias';    

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
        'media_status' =>  MediaStatus::class,
    ];

    public $incrementing = false;

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
