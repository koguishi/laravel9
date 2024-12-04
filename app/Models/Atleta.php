<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atleta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'atletas';

    protected $fillable = [
        'id',
        'nome',
        'dt_nascimento',
    ];

    protected $casts = [
        'id' => 'string'
    ];

    public $incrementing = false;
}
