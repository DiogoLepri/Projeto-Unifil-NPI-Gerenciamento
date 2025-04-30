<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'solicitacoes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'grupo_id',
        'status',
    ];

    /**
     * Get the user that owns the solicitacao.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the grupo that owns the solicitacao.
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}