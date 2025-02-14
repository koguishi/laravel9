<?php

namespace Tests\Unit\App\Models;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new Categoria();
    }

    protected function traits(): array
    {
        return [
            HasFactory::class,
            SoftDeletes::class,
        ];
    }

    protected function fillables(): array
    {
        return [
            'id',
            'nome',
            'descricao',
            'ativo',
        ];
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'ativo' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }    
}
