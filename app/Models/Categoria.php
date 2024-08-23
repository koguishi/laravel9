<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'id' => 'string',
        'ativo' => 'boolean',
    ];

    public $incrementing = false;
}
