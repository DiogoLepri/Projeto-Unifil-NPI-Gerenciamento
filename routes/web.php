<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\HackathonController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and will be assigned to
| the "web" middleware group.
|
*/

// Rotas públicas
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rotas de autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rotas protegidas
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Projetos de Extensão
    Route::get('/projetos', [ProjetoController::class, 'index'])->name('projetos.index');
    Route::get('/projetos/create', [ProjetoController::class, 'create'])->name('projetos.create');
    Route::post('/projetos', [ProjetoController::class, 'store'])->name('projetos.store');
    Route::get('/projetos/{projeto}', [ProjetoController::class, 'show'])->name('projetos.show');
    Route::get('/projetos/{projeto}/edit', [ProjetoController::class, 'edit'])->name('projetos.edit');
    Route::put('/projetos/{projeto}', [ProjetoController::class, 'update'])->name('projetos.update');
    Route::delete('/projetos/{projeto}', [ProjetoController::class, 'destroy'])->name('projetos.destroy');
    Route::post('/projetos/{projeto}/participar', [ProjetoController::class, 'participar'])->name('projetos.participar');
    Route::post('/projetos/{projeto}/solicitar', [ProjetoController::class, 'solicitar'])->name('projetos.solicitar');
    Route::get('/projetos/escolher-metodo', [ProjetoController::class, 'escolherMetodo'])->name('projetos.escolher-metodo');
    
    // Hackathons
    Route::get('/hackathons', [HackathonController::class, 'index'])->name('hackathons.index');
    Route::get('/hackathons/create', [HackathonController::class, 'create'])->name('hackathons.create');
    Route::post('/hackathons', [HackathonController::class, 'store'])->name('hackathons.store');
    Route::get('/hackathons/{hackathon}', [HackathonController::class, 'show'])->name('hackathons.show');
    Route::get('/hackathons/{hackathon}/edit', [HackathonController::class, 'edit'])->name('hackathons.edit');
    Route::put('/hackathons/{hackathon}', [HackathonController::class, 'update'])->name('hackathons.update');
    Route::delete('/hackathons/{hackathon}', [HackathonController::class, 'destroy'])->name('hackathons.destroy');
    
    // Grupos
    Route::get('/grupos', [GrupoController::class, 'index'])->name('grupos.index');
    Route::get('/grupos/create', [GrupoController::class, 'create'])->name('grupos.create');
    Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
    Route::get('/grupos/{grupo}', [GrupoController::class, 'show'])->name('grupos.show');
    Route::get('/grupos/{grupo}/edit', [GrupoController::class, 'edit'])->name('grupos.edit');
    Route::put('/grupos/{grupo}', [GrupoController::class, 'update'])->name('grupos.update');
    Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy'])->name('grupos.destroy');
    Route::post('/grupos/{grupo}/solicitar', [GrupoController::class, 'solicitar'])->name('grupos.solicitar');
    Route::post('/grupos/{grupo}/aceitar-solicitacao/{solicitacao}', [GrupoController::class, 'aceitarSolicitacao'])->name('grupos.aceitar-solicitacao');
    Route::delete('/grupos/{grupo}/rejeitar-solicitacao/{solicitacao}', [GrupoController::class, 'rejeitarSolicitacao'])->name('grupos.rejeitar-solicitacao');
    Route::post('/grupos/{grupo}/sair', [GrupoController::class, 'sair'])->name('grupos.sair');
    Route::post('/grupos/{grupo}/finalizar', [GrupoController::class, 'finalizar'])->name('grupos.finalizar');
    Route::post('/grupos/{grupo}/upload-projeto', [GrupoController::class, 'uploadProjeto'])->name('grupos.upload-projeto');
    Route::get('/grupos/{grupo}/download-projeto', [GrupoController::class, 'downloadProjeto'])->name('grupos.download-projeto');
    Route::post('/grupos/{grupo}/validar', [GrupoController::class, 'validar'])->name('grupos.validar');
    Route::delete('/grupos/{grupo}/remover-participante/{user}', [GrupoController::class, 'removerParticipante'])->name('grupos.remover-participante');
    
    // User Profile Routes
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
Route::get('/profile/delete', [ProfileController::class, 'confirmDelete'])->name('profile.confirm-delete');
Route::delete('/profile', [ProfileController::class, 'delete'])->name('profile.delete');

    
    // Rotas apenas para professores
    Route::middleware(['professor'])->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    });
});