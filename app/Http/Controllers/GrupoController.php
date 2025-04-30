<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Hackathon;
use App\Models\User;
use App\Models\Solicitacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hackathon_id = $request->query('hackathon_id');
        
        if (!$hackathon_id) {
            return redirect()->route('hackathons.index')
                ->with('error', 'Hackathon não especificado.');
        }
        
        $hackathon = Hackathon::findOrFail($hackathon_id);
        $grupos = $hackathon->grupos;
        
        $user = Auth::user();
        $userInGrupo = false;
        $userIsGrupoLeader = false;
        
        // Check if user is in any group of this hackathon
        foreach ($grupos as $grupo) {
            if ($grupo->participantes()->where('user_id', $user->id)->exists()) {
                $userInGrupo = true;
                
                // Check if user is the leader of any group
                if ($grupo->lider_id == $user->id) {
                    $userIsGrupoLeader = true;
                }
                
                break;
            }
        }
        
        return view('grupos.index', compact('hackathon', 'grupos', 'userInGrupo', 'userIsGrupoLeader'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $hackathon_id = $request->query('hackathon_id');
        
        if (!$hackathon_id) {
            return redirect()->route('hackathons.index')
                ->with('error', 'Hackathon não especificado.');
        }
        
        $hackathon = Hackathon::findOrFail($hackathon_id);
        
        // Check if hackathon is open
        if ($hackathon->status != 'Aberto') {
            return redirect()->route('hackathons.show', $hackathon)
                ->with('error', 'Este hackathon está fechado para novos grupos.');
        }
        
        // Check if user is already in a group for this hackathon
        $user = Auth::user();
        foreach ($hackathon->grupos as $grupo) {
            if ($grupo->participantes()->where('user_id', $user->id)->exists()) {
                return redirect()->route('grupos.index', ['hackathon_id' => $hackathon->id])
                    ->with('error', 'Você já está em um grupo para este hackathon.');
            }
        }
        
        $usuarios = User::where('role', 'aluno')->get();
        
        return view('grupos.create', compact('hackathon', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'hackathon_id' => 'required|exists:hackathons,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'participantes' => 'nullable|array',
            'participantes.*' => 'exists:users,id',
        ]);
        
        $hackathon = Hackathon::findOrFail($validatedData['hackathon_id']);
        
        // Check if hackathon is open
        if ($hackathon->status != 'Aberto') {
            return redirect()->route('hackathons.show', $hackathon)
                ->with('error', 'Este hackathon está fechado para novos grupos.');
        }
        
        // Create new group
        $grupo = new Grupo();
        $grupo->hackathon_id = $validatedData['hackathon_id'];
        $grupo->nome = $validatedData['nome'];
        $grupo->descricao = $validatedData['descricao'] ?? null;
        $grupo->lider_id = Auth::id();
        $grupo->status = 'Aberto';
        $grupo->save();
        
        // Add leader to the group
        $grupo->participantes()->attach(Auth::id(), ['funcao' => 'Líder']);
        
        // Add other participants if selected
        if (isset($validatedData['participantes']) && !empty($validatedData['participantes'])) {
            foreach ($validatedData['participantes'] as $participante_id) {
                $grupo->participantes()->attach($participante_id, ['funcao' => 'Membro']);
            }
        }
        
        return redirect()->route('grupos.show', $grupo)
            ->with('success', 'Grupo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grupo $grupo)
    {
        $userInGrupo = false;
        $userIsGrupoLeader = false;
        $solicitacoesPendentes = [];
        
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user is in the group
            $userInGrupo = $grupo->participantes()->where('user_id', $user->id)->exists();
            
            // Check if user is the leader
            $userIsGrupoLeader = ($grupo->lider_id == $user->id);
            
            // Get pending requests if user is the leader
            if ($userIsGrupoLeader) {
                $solicitacoesPendentes = Solicitacao::where('grupo_id', $grupo->id)
                    ->where('status', 'Pendente')
                    ->get();
            }
        }
        
        return view('grupos.show', compact('grupo', 'userInGrupo', 'userIsGrupoLeader', 'solicitacoesPendentes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        // Check if user is the leader
        if (Auth::id() != $grupo->lider_id && Auth::user()->role != 'professor') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Apenas o líder pode editar o grupo.');
        }
        
        $usuarios = User::where('role', 'aluno')->get();
        $participantesIds = $grupo->participantes->pluck('id')->toArray();
        
        return view('grupos.edit', compact('grupo', 'usuarios', 'participantesIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        // Check if user is the leader
        if (Auth::id() != $grupo->lider_id && Auth::user()->role != 'professor') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Apenas o líder pode editar o grupo.');
        }
        
        // Check if group is open
        if ($grupo->status != 'Aberto') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Este grupo está fechado para edições.');
        }
        
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'participantes' => 'nullable|array',
            'participantes.*' => 'exists:users,id',
        ]);
        
        $grupo->nome = $validatedData['nome'];
        $grupo->descricao = $validatedData['descricao'] ?? null;
        $grupo->save();
        
        // Update participants
        $currentParticipantes = $grupo->participantes->pluck('id')->toArray();
        $newParticipantes = $validatedData['participantes'] ?? [];
        
        // Add leader if not in the list
        if (!in_array($grupo->lider_id, $newParticipantes)) {
            $newParticipantes[] = $grupo->lider_id;
        }
        
        // Detach participants not in the new list
        foreach ($currentParticipantes as $participante_id) {
            if (!in_array($participante_id, $newParticipantes) && $participante_id != $grupo->lider_id) {
                $grupo->participantes()->detach($participante_id);
            }
        }
        
        // Attach new participants
        foreach ($newParticipantes as $participante_id) {
            if (!in_array($participante_id, $currentParticipantes)) {
                $funcao = ($participante_id == $grupo->lider_id) ? 'Líder' : 'Membro';
                $grupo->participantes()->attach($participante_id, ['funcao' => $funcao]);
            }
        }
        
        return redirect()->route('grupos.show', $grupo)
            ->with('success', 'Grupo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        // Check if user is a professor
        if (Auth::user()->role != 'professor') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Apenas professores podem excluir grupos.');
        }
        
        $hackathon_id = $grupo->hackathon_id;
        $grupo->delete();
        
        return redirect()->route('grupos.index', ['hackathon_id' => $hackathon_id])
            ->with('success', 'Grupo excluído com sucesso!');
    }
    
    /**
     * Request to join a group.
     */
    public function solicitar(Grupo $grupo)
    {
        // Check if group is open
        if ($grupo->status != 'Aberto') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Este grupo está fechado para novos membros.');
        }
        
        // Check if user is already in the group
        $user = Auth::user();
        if ($grupo->participantes()->where('user_id', $user->id)->exists()) {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Você já é membro deste grupo.');
        }
        
        // Check if user already has a pending request
        $solicitacaoExistente = Solicitacao::where('user_id', $user->id)
            ->where('grupo_id', $grupo->id)
            ->where('status', 'Pendente')
            ->first();
            
        if ($solicitacaoExistente) {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Você já tem uma solicitação pendente para este grupo.');
        }
        
        // Create new request
        $solicitacao = new Solicitacao();
        $solicitacao->user_id = $user->id;
        $solicitacao->grupo_id = $grupo->id;
        $solicitacao->status = 'Pendente';
        $solicitacao->save();
        
        return redirect()->route('grupos.show', $grupo)
            ->with('success', 'Solicitação enviada com sucesso! Aguarde a aprovação do líder do grupo.');
    }
    
    /**
     * Accept a join request.
     */
    public function aceitarSolicitacao($solicitacao_id)
    {
        $solicitacao = Solicitacao::findOrFail($solicitacao_id);
        $grupo = $solicitacao->grupo;
        
        // Check if user is the leader
        if (Auth::id() != $grupo->lider_id && Auth::user()->role != 'professor') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Apenas o líder pode aceitar solicitações.');
        }
        
        // Check if group is open
        if ($grupo->status != 'Aberto') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Este grupo está fechado para novos membros.');
        }
        
        // Update request status
        $solicitacao->status = 'Aceito';
        $solicitacao->save();
        
        // Add user to the group
        $grupo->participantes()->attach($solicitacao->user_id, ['funcao' => 'Membro']);
        
        return redirect()->route('grupos.show', $grupo)
            ->with('success', 'Solicitação aceita com sucesso!');
    }
    
    /**
     * Reject a join request.
     */
    public function rejeitarSolicitacao($solicitacao_id)
    {
        $solicitacao = Solicitacao::findOrFail($solicitacao_id);
        $grupo = $solicitacao->grupo;
        
        // Check if user is the leader
        if (Auth::id() != $grupo->lider_id && Auth::user()->role != 'professor') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Apenas o líder pode rejeitar solicitações.');
        }
        
        // Update request status
        $solicitacao->status = 'Rejeitado';
        $solicitacao->save();
        
        return redirect()->route('grupos.show', $grupo)
            ->with('success', 'Solicitação rejeitada.');
    }
    
    /**
     * Leave a group.
     */
    public function sair(Grupo $grupo)
    {
        $user = Auth::user();
        
        // Check if user is in the group
        if (!$grupo->participantes()->where('user_id', $user->id)->exists()) {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Você não é membro deste grupo.');
        }
        
        // Check if user is the leader
        if ($user->id == $grupo->lider_id) {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'O líder não pode sair do grupo. Transfira a liderança para outro membro primeiro.');
        }
        
        // Remove user from the group
        $grupo->participantes()->detach($user->id);
        
        return redirect()->route('grupos.index', ['hackathon_id' => $grupo->hackathon_id])
            ->with('success', 'Você saiu do grupo com sucesso.');
    }
    
    /**
     * Finalize a group.
     */
    public function finalizar(Grupo $grupo)
    {
        // Check if user is the leader
        if (Auth::id() != $grupo->lider_id && Auth::user()->role != 'professor') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Apenas o líder pode finalizar o grupo.');
        }
        
        // Check if group has enough participants (at least 3)
        if ($grupo->participantes->count() < 3) {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'O grupo precisa ter pelo menos 3 participantes para ser finalizado.');
        }
        
        // Close the group
        $grupo->status = 'Fechado';
        $grupo->save();
        
        return redirect()->route('grupos.show', $grupo)
            ->with('success', 'Grupo finalizado com sucesso!');
    }
    
    /**
     * Upload project file.
     */
    public function uploadProjeto(Request $request, Grupo $grupo)
    {
        // Check if user is the leader
        if (Auth::id() != $grupo->lider_id && Auth::user()->role != 'professor') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Apenas o líder pode enviar o projeto.');
        }
        
        $validatedData = $request->validate([
            'projeto_nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'projeto_arquivo' => 'required|file|max:10240', // Max 10MB
        ]);
        
        // Store project file
        $path = $request->file('projeto_arquivo')->store('projetos');
        
        // Update group
        $grupo->projeto_nome = $validatedData['projeto_nome'];
        $grupo->descricao = $validatedData['descricao'];
        $grupo->projeto_arquivo = $path;
        $grupo->save();
        
        return redirect()->route('grupos.show', $grupo)
            ->with('success', 'Projeto enviado com sucesso!');
    }
    
    /**
     * Download project file.
     */
    public function downloadProjeto(Grupo $grupo)
    {
        if (!$grupo->projeto_arquivo) {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Este grupo ainda não enviou um projeto.');
        }
        
        return Storage::download($grupo->projeto_arquivo);
    }
    
    /**
     * Validate a group.
     */
    public function validar(Request $request, Grupo $grupo)
    {
        // Check if user is a professor
        if (Auth::user()->role != 'professor') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Apenas professores podem validar grupos.');
        }
        
        $validatedData = $request->validate([
            'feedback' => 'nullable|string',
            'nota' => 'required|numeric|min:0|max:10',
        ]);
        
        // Update group
        $grupo->feedback = $validatedData['feedback'];
        $grupo->nota = $validatedData['nota'];
        $grupo->validado = true;
        $grupo->save();
        
        return redirect()->route('grupos.show', $grupo)
            ->with('success', 'Grupo validado com sucesso!');
    }
    
    /**
     * Remove a participant from the group.
     */
    public function removerParticipante(Request $request, $grupo_id, $user_id)
    {
        $grupo = Grupo::findOrFail($grupo_id);
        
        // Check if user is the leader
        if (Auth::id() != $grupo->lider_id && Auth::user()->role != 'professor') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Apenas o líder pode remover participantes.');
        }
        
        // Check if group is open
        if ($grupo->status != 'Aberto') {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'Este grupo está fechado para edições.');
        }
        
        // Check if trying to remove the leader
        if ($user_id == $grupo->lider_id) {
            return redirect()->route('grupos.show', $grupo)
                ->with('error', 'O líder não pode ser removido do grupo.');
        }
        
        // Remove user from the group
        $grupo->participantes()->detach($user_id);
        
        return redirect()->route('grupos.show', $grupo)
            ->with('success', 'Participante removido com sucesso.');
    }
}