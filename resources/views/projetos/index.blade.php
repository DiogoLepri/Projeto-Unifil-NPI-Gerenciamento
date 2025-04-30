@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Projetos de Extensão</h2>
        </div>
        <div class="col-md-4 text-end">
            @if(Auth::user()->role == 'professor')
            <a href="{{ route('projetos.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Novo Projeto
            </a>
            @endif
        </div>
    </div>

    <div class="row">
        @if($projetos->isEmpty())
            <div class="col-12">
                <div class="alert alert-info">
                    Nenhum projeto de extensão disponível no momento.
                </div>
            </div>
        @else
            @foreach($projetos as $projeto)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-orange d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $projeto->codigo }}</h5>
                            <span class="badge {{ $projeto->status == 'aberto' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($projeto->status) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $projeto->nome }}</h5>
                            <p class="card-text">{{ Str::limit($projeto->descricao, 100) }}</p>
                            <p class="text-muted">
                                <strong>Professor:</strong> {{ $projeto->professor->name ?? 'Não definido' }}
                            </p>
                        </div>
                        <div class="card-footer bg-light">
                            <a href="{{ route('projetos.show', $projeto) }}" class="btn btn-primary btn-sm">
                                Ver detalhes
                            </a>
                            
                            @if(Auth::user()->role == 'professor' && Auth::id() == $projeto->professor_id)
                                <a href="{{ route('projetos.edit', $projeto) }}" class="btn btn-warning btn-sm">
                                    Editar
                                </a>
                                <form action="{{ route('projetos.destroy', $projeto) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este projeto?')">
                                        Excluir
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection