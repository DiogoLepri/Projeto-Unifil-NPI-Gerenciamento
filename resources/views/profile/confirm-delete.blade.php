@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Confirmar Exclusão da Conta</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Atenção!</strong> Esta ação é irreversível.
                    </div>
                    
                    <p>Ao excluir sua conta:</p>
                    <ul>
                        <li>Todos os seus dados pessoais serão removidos</li>
                        <li>Você será removido de todos os projetos e grupos</li>
                        <li>Você não poderá recuperar sua conta</li>
                    </ul>
                    
                    <form action="{{ route('profile.delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Confirme sua senha para continuar</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem absoluta certeza que deseja excluir sua conta permanentemente?')">
                                <i class="bi bi-trash"></i> Excluir Minha Conta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection