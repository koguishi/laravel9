<?php

namespace Tests\Unit\App\Models;

use App\Models\Media;
use App\Models\Traits\UuidTrait;
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
            'deleted_at' => 'datetime',
        ];
    }    
}
