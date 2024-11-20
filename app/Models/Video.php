<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'titulo',
        'descricao',
        'dt_filmagem',
        'created_at',
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        // 'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class);
    }

    // public function atletas()
    // {
    //     return $this->belongsToMany(Atleta::class, 'cast_member_video');
    // }

    // public function media()
    // {
    //     return $this->hasOne(Media::class)
    //                     ->where('type', (string) MediaTypes::VIDEO->value);
    // }
}