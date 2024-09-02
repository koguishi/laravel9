<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory, SoftDeletes;

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
