<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Grupo;
use App\Models\Projeto;
use App\Models\Solicitacao;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's groups
        $meusGrupos = Grupo::whereHas('participantes', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('hackathon')->get();
        
        // Get user's projects
        $meusProjetos = Projeto::whereHas('participantes', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('professor')->get();
        
        // For professors, get pending validations
        $gruposPendentes = [];
        $projetosPendentes = [];
        
        if ($user->role == 'professor') {
            // Get groups that need validation (status is Fechado but not yet validated)
            $gruposPendentes = Grupo::where('status', 'Fechado')
                ->where('validado', false)
                ->whereNotNull('projeto_arquivo')
                ->with('hackathon', 'lider')
                ->get();
            
            // Get pending project requests
            $projetosPendentes = Solicitacao::where('status', 'Pendente')
                ->whereHas('projeto', function ($query) use ($user) {
                    $query->where('professor_id', $user->id);
                })
                ->with('user', 'projeto')
                ->get();
        }
        
        return view('dashboard', compact('meusGrupos', 'meusProjetos', 'gruposPendentes', 'projetosPendentes'));
    }
}