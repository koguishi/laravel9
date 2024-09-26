<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atleta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'nome',
        'dtNascimento',
    ];

    protected $casts = [
        'id' => 'string'
    ];

    public $incrementing = false;
}
