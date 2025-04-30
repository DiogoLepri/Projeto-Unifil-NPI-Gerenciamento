<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }
        
        $usuarios = User::all();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }
        
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }
        
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'matricula' => ['required', 'string', 'max:20', 'unique:users'],
            'curso' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:aluno,professor'],
        ]);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'matricula' => $validatedData['matricula'],
            'curso' => $validatedData['curso'],
            'role' => $validatedData['role'],
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        // Check if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }
        
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        // Check if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }
        
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'matricula' => ['required', 'string', 'max:20', 'unique:users,matricula,' . $usuario->id],
            'curso' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:aluno,professor'],
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:8', 'confirmed'],
            ]);
            
            $usuario->password = Hash::make($request->password);
        }

        $usuario->name = $validatedData['name'];
        $usuario->email = $validatedData['email'];
        $usuario->matricula = $validatedData['matricula'];
        $usuario->curso = $validatedData['curso'];
        $usuario->role = $validatedData['role'];
        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        // Check if user is a professor
        if (Auth::user()->role !== 'professor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }
        
        // Prevent self-deletion
        if ($usuario->id === Auth::id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Você não pode excluir seu próprio usuário.');
        }
        
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}