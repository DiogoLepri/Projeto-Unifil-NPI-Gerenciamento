<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Hackathon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjetoController extends Controller
{
    public function index()
    {
        $projetos = Projeto::all();
        $hackathons = Hackathon::all(); // Adding this to fix your previous error
        
        return view('projetos.index', compact('projetos', 'hackathons'));
    }

    public function create()
    {
        $professores = User::where('role', 'professor')->get();
        return view('projetos.create', compact('professores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'codigo' => 'required|string|max:20|unique:projetos',
            'descricao' => 'required|string',
            'professor_id' => 'required|exists:users,id',
            'status' => 'required|in:aberto,fechado'
        ]);

        $projeto = Projeto::create($validated);

        return redirect()->route('projetos.index')
            ->with('success', 'Projeto criado com sucesso!');
    }

    public function show(Projeto $projeto)
    {
        return view('projetos.show', compact('projeto'));
    }

    public function edit(Projeto $projeto)
    {
        $professores = User::where('role', 'professor')->get();
        return view('projetos.edit', compact('projeto', 'professores'));
    }

    public function update(Request $request, Projeto $projeto)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'codigo' => 'required|string|max:20|unique:projetos,codigo,' . $projeto->id,
            'descricao' => 'required|string',
            'professor_id' => 'required|exists:users,id',
            'status' => 'required|in:aberto,fechado'
        ]);

        $projeto->update($validated);

        return redirect()->route('projetos.index')
            ->with('success', 'Projeto atualizado com sucesso!');
    }

    public function destroy(Projeto $projeto)
    {
        $projeto->delete();

        return redirect()->route('projetos.index')
            ->with('success', 'Projeto removido com sucesso!');
    }

    public function participar(Projeto $projeto)
    {
        $user = Auth::user();
        
        // Check if user already participates
        if ($projeto->participantes()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Você já participa deste projeto.');
        }
        
        // Add user to the project
        $projeto->participantes()->attach($user->id, [
            'status' => 'entrou',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('projetos.show', $projeto)
            ->with('success', 'Você agora participa deste projeto!');
    }

    public function solicitar(Projeto $projeto)
    {
        $user = Auth::user();
        
        // Check if user already requested
        if ($projeto->participantes()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Você já solicitou participação neste projeto.');
        }
        
        // Add user to the project with pending status
        $projeto->participantes()->attach($user->id, [
            'status' => 'solicitado',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('projetos.show', $projeto)
            ->with('success', 'Solicitação enviada com sucesso!');
    }
}  