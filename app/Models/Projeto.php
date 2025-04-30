<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'professor_id',
        'status',
    ];

    /**
     * Get the professor that owns the projeto.
     */
    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    /**
     * The participants that belong to the projeto.
     */
    public function participantes()
    {
        return $this->belongsToMany(User::class, 'projeto_user')
                    ->withTimestamps();
    }
    // In app/Models/User.php
    public function projetos()
    {
        return $this->belongsToMany(Projeto::class, 'projeto_user')
                    ->withTimestamps();
    }
}