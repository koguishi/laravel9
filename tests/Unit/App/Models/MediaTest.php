<?php

namespace Tests\Unit\App\Models;

use App\Models\Media;
use App\Models\Traits\UuidTrait;
use core\domain\enum\MediaStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new Media();
    }

    protected function traits(): array
    {
        return [
            HasFactory::class,
            UuidTrait::class,
        ];
    }

    protected function fillables(): array
    {
        return [
            'file_path',
            'encoded_path',
            'media_status',
        ];
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            // 'ativo' => 'boolean',
            'video_id' => 'string',            
            'media_status' =>  MediaStatus::class,
            // 'deleted_at' => 'datetime',
        ];
    }    
}
