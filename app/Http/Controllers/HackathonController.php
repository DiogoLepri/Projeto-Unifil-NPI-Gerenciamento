<?php

namespace App\Http\Controllers;

use App\Models\Hackathon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HackathonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hackathons = Hackathon::all();
        return view('hackathons.index', compact('hackathons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Verify if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('hackathons.index')
                ->with('error', 'Você não tem permissão para criar hackathons.');
        }

        return view('hackathons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verify if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('hackathons.index')
                ->with('error', 'Você não tem permissão para criar hackathons.');
        }

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'local' => 'required|string|max:255',
            'descricao' => 'required|string',
            'criterios_avaliacao' => 'required|string',
            'status' => 'required|in:Aberto,Fechado',
        ]);

        Hackathon::create($validatedData);

        return redirect()->route('hackathons.index')
            ->with('success', 'Hackathon criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hackathon $hackathon)
    {
        $userInGrupo = false;
        
        // Check if user is in any group of this hackathon
        if (Auth::check()) {
            $user = Auth::user();
            foreach ($hackathon->grupos as $grupo) {
                if ($grupo->participantes()->where('user_id', $user->id)->exists()) {
                    $userInGrupo = true;
                    break;
                }
            }
        }
        
        return view('hackathons.show', compact('hackathon', 'userInGrupo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hackathon $hackathon)
    {
        // Verify if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('hackathons.index')
                ->with('error', 'Você não tem permissão para editar hackathons.');
        }

        return view('hackathons.edit', compact('hackathon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hackathon $hackathon)
    {
        // Verify if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('hackathons.index')
                ->with('error', 'Você não tem permissão para editar hackathons.');
        }

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'local' => 'required|string|max:255',
            'descricao' => 'required|string',
            'criterios_avaliacao' => 'required|string',
            'status' => 'required|in:Aberto,Fechado',
        ]);

        $hackathon->update($validatedData);

        return redirect()->route('hackathons.show', $hackathon)
            ->with('success', 'Hackathon atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hackathon $hackathon)
    {
        // Verify if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('hackathons.index')
                ->with('error', 'Você não tem permissão para excluir hackathons.');
        }

        $hackathon->delete();

        return redirect()->route('hackathons.index')
            ->with('success', 'Hackathon excluído com sucesso!');
    }
}