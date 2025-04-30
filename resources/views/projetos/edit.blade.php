@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Editar Projeto de Extensão</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('projetos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-orange">
                    <h5 class="mb-0">Editar Informações do Projeto</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('projetos.update', $projeto) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código do Projeto *</label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                   id="codigo" name="codigo" value="{{ old('codigo', $projeto->codigo) }}" required>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Projeto *</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" name="nome" value="{{ old('nome', $projeto->nome) }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição *</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                      id="descricao" name="descricao" rows="5" required>{{ old('descricao', $projeto->descricao) }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="professor_id" class="form-label">Professor Responsável *</label>
                            <select class="form-select @error('professor_id') is-invalid @enderror" 
                                    id="professor_id" name="professor_id" required>
                                <option value="">Selecione o professor</option>
                                @foreach($professores as $professor)
                                    <option value="{{ $professor->id }}" 
                                        {{ old('professor_id', $projeto->professor_id) == $professor->id ? 'selected' : '' }}>
                                        {{ $professor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('professor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="aberto" {{ old('status', $projeto->status) == 'aberto' ? 'selected' : '' }}>Aberto</option>
                                <option value="fechado" {{ old('status', $projeto->status) == 'fechado' ? 'selected' : '' }}>Fechado</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-circle"></i> Atualizar Projeto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection