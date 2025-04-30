@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #D2901E; color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">GRUPO {{ strtoupper($grupo->nome) }}</h5>
                        <span class="badge {{ $grupo->status == 'Aberto' ? 'bg-success' : 'bg-danger' }}">
                            {{ strtoupper($grupo->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Hackathon:</strong> {{ $grupo->hackathon->nome }}</p>
                            <p><strong>Líder do Grupo:</strong> {{ $grupo->lider->nome }}</p>
                            <p><strong>Data de Criação:</strong> {{ date('d/m/Y', strtotime($grupo->created_at)) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Projeto:</strong> {{ $grupo->projeto_nome ?? 'Não definido' }}</p>
                            <p><strong>Descrição:</strong> {{ $grupo->descricao ?? 'Não definido' }}</p>
                        </div>
                    </div>

                    <h5 class="mb-3">PARTICIPANTES</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>NOME</th>
                                    <th>MATRÍCULA</th>
                                    <th>CURSO</th>
                                    <th>FUNÇÃO</th>
                                    @if($userIsGrupoLeader && $grupo->status == 'Aberto')
                                    <th>AÇÕES</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grupo->participantes as $participante)
                                <tr>
                                    <td>{{ $participante->nome }}</td>
                                    <td>{{ $participante->matricula }}</td>
                                    <td>{{ $participante->curso }}</td>
                                    <td>{{ $participante->pivot->funcao ?? 'Membro' }}</td>
                                    @if($userIsGrupoLeader && $grupo->status == 'Aberto')
                                    <td>
                                        @if($participante->id != auth()->id())
                                        <form action="{{ route('grupos.remover-participante', ['grupo_id' => $grupo->id, 'user_id' => $participante->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja remover este participante?')">REMOVER</button>
                                        </form>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($userIsGrupoLeader && $grupo->status == 'Aberto')
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">SOLICITAÇÕES PENDENTES</h6>
                        </div>
                        <div class="card-body">
                            @if(count($solicitacoesPendentes) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>NOME</th>
                                            <th>MATRÍCULA</th>
                                            <th>CURSO</th>
                                            <th>DATA</th>
                                            <th>AÇÕES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($solicitacoesPendentes as $solicitacao)
                                        <tr>
                                            <td>{{ $solicitacao->user->nome }}</td>
                                            <td>{{ $solicitacao->user->matricula }}</td>
                                            <td>{{ $solicitacao->user->curso }}</td>
                                            <td>{{ date('d/m/Y', strtotime($solicitacao->created_at)) }}</td>
                                            <td>
                                                <form action="{{ route('grupos.aceitar-solicitacao', $solicitacao->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">ACEITAR</button>
                                                </form>
                                                <form action="{{ route('grupos.rejeitar-solicitacao', $solicitacao->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">REJEITAR</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="text-muted">Não há solicitações pendentes.</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($grupo->status == 'Aberto')
                        @if($userIsGrupoLeader)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <a href="{{ route('grupos.edit', $grupo->id) }}" class="btn btn-warning me-2">EDITAR GRUPO</a>
                                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    ENVIAR PROJETO
                                </button>
                                @if(count($grupo->participantes) >= 3)
                                <form action="{{ route('grupos.finalizar', $grupo->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Tem certeza que deseja finalizar este grupo? Não será possível fazer alterações depois.')">FINALIZAR GRUPO</button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @elseif(!$userInGrupo)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form action="{{ route('grupos.solicitar', $grupo->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">SOLICITAR PARTICIPAÇÃO</button>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form action="{{ route('grupos.sair', $grupo->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja sair deste grupo?')">SAIR DO GRUPO</button>
                                </form>
                            </div>
                        </div>
                        @endif
                    @endif

                    @if(auth()->user()->role == 'professor')
                    <hr>
                    <h5 class="mb-3">VALIDAÇÃO DO PROJETO</h5>
                    <div class="row">
                        <div class="col-md-6">
                            @if($grupo->projeto_arquivo)
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">PROJETO ENVIADO</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Arquivo:</strong> {{ $grupo->projeto_arquivo }}</p>
                                    <p><strong>Enviado em:</strong> {{ date('d/m/Y', strtotime($grupo->updated_at)) }}</p>
                                    <a href="{{ route('grupos.download-projeto', $grupo->id) }}" class="btn btn-primary">DOWNLOAD</a>
                                </div>
                            </div>
                            @else
                            <p class="text-muted">Nenhum projeto enviado ainda.</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">VALIDAR GRUPO</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('grupos.validar', $grupo->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="feedback" class="form-label">Feedback</label>
                                            <textarea class="form-control" name="feedback" id="feedback" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nota" class="form-label">Nota</label>
                                            <input type="number" class="form-control" name="nota" id="nota" min="0" max="10" step="0.1">
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">VALIDAR</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Upload do Projeto -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Enviar Projeto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('grupos.upload-projeto', $grupo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="projeto_nome" class="form-label">Nome do Projeto</label>
                        <input type="text" class="form-control" id="projeto_nome" name="projeto_nome" value="{{ $grupo->projeto_nome }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required>{{ $grupo->descricao }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="projeto_arquivo" class="form-label">Arquivo do Projeto</label>
                        <input type="file" class="form-control" id="projeto_arquivo" name="projeto_arquivo" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-primary">ENVIAR</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection