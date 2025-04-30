<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hackathon_id',
        'nome',
        'descricao',
        'lider_id',
        'projeto_nome',
        'projeto_arquivo',
        'status',
        'feedback',
        'nota',
        'validado',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'validado' => 'boolean',
    ];

    /**
     * Get the hackathon that owns the grupo.
     */
    public function hackathon()
    {
        return $this->belongsTo(Hackathon::class);
    }

    /**
     * Get the leader of the grupo.
     */
    public function lider()
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    /**
     * The participants that belong to the grupo.
     */
    public function participantes()
    {
        return $this->belongsToMany(User::class, 'grupo_user')
                    ->withPivot('funcao')
                    ->withTimestamps();
    }

    /**
     * Get the solicitations for the grupo.
     */
    public function solicitacoes()
    {
        return $this->hasMany(Solicitacao::class);
    }
}