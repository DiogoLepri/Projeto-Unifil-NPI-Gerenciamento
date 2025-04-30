@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Detalhes do Projeto</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('projetos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-orange d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $projeto->codigo }}</h5>
                    <span class="badge {{ $projeto->status == 'aberto' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($projeto->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ $projeto->nome }}</h4>
                    
                    <div class="mb-4">
                        <h5>Descrição</h5>
                        <p>{{ $projeto->descricao }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Professor Responsável</h5>
                        <p>{{ $projeto->professor->name ?? 'Não definido' }}</p>
                    </div>
                    
                    @if(Auth::user()->role == 'professor' && Auth::id() == $projeto->professor_id)
                        <div class="d-grid gap-2 d-md-flex">
                            <a href="{{ route('projetos.edit', $projeto) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form action="{{ route('projetos.destroy', $projeto) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este projeto?')">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-orange">
                    <h5 class="mb-0">Participantes</h5>
                </div>
                <div class="card-body">
                    @if($projeto->participantes->isEmpty())
                        <p class="text-muted">Nenhum participante registrado</p>
                    @else
                        <ul class="list-group">
                            @foreach($projeto->participantes->where('pivot.status', 'entrou') as $participante)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $participante->name }}
                                    @if(Auth::user()->role == 'professor' && Auth::id() == $projeto->professor_id)
                                        <form action="{{ route('projetos.remover-participante', [$projeto, $participante]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Remover este participante?')">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            
            @if(Auth::user()->role == 'professor' && Auth::id() == $projeto->professor_id)
                <div class="card mb-4">
                    <div class="card-header bg-orange">
                        <h5 class="mb-0">Solicitações Pendentes</h5>
                    </div>
                    <div class="card-body">
                        @php $solicitacoes = $projeto->participantes->where('pivot.status', 'solicitado'); @endphp
                        
                        @if($solicitacoes->isEmpty())
                            <p class="text-muted">Nenhuma solicitação pendente</p>
                        @else
                            <ul class="list-group">
                                @foreach($solicitacoes as $solicitante)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            {{ $solicitante->name }}
                                        </div>
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('projetos.aceitar-solicitacao', [$projeto, $solicitante]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Aceitar</button>
                                            </form>
                                            <form action="{{ route('projetos.rejeitar-solicitacao', [$projeto, $solicitante]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Rejeitar</button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endif
            
            @if(Auth::user()->role == 'aluno')
                @php
                    $participacao = $projeto->participantes->where('id', Auth::id())->first();
                    $status = $participacao ? $participacao->pivot->status : null;
                @endphp
                
                <div class="card">
                    <div class="card-header bg-orange">
                        <h5 class="mb-0">Ações</h5>
                    </div>
                    <div class="card-body">
                        @if($projeto->status == 'fechado')
                            <div class="alert alert-warning">
                                Este projeto está fechado para novas inscrições.
                            </div>
                        @elseif(!$status)
                            <form action="{{ route('projetos.solicitar', $projeto) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    Solicitar Participação
                                </button>
                            </form>
                        @elseif($status == 'solicitado')
                            <div class="alert alert-info">
                                Sua solicitação está pendente de aprovação.
                            </div>
                        @elseif($status == 'entrou')
                            <div class="alert alert-success">
                                Você é um participante deste projeto.
                            </div>
                            <form action="{{ route('projetos.sair', $projeto) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tem certeza que deseja sair deste projeto?')">
                                    Sair do Projeto
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection