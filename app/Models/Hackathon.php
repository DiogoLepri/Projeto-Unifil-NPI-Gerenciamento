<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hackathon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'data_inicio',
        'data_fim',
        'local',
        'descricao',
        'criterios_avaliacao',
        'status',
    ];

    /**
     * Get the grupos for the hackathon.
     */
    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }
}