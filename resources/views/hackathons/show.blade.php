@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #D2901E; color: white;">
                    <h5 class="mb-0">HACKATHON {{ strtoupper($hackathon->nome) }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Data:</strong> {{ date('d/m/Y', strtotime($hackathon->data_inicio)) }} - {{ date('d/m/Y', strtotime($hackathon->data_fim)) }}</p>
                            <p><strong>Local:</strong> {{ $hackathon->local }}</p>
                            <p><strong>Status:</strong> <span class="badge {{ $hackathon->status == 'Aberto' ? 'bg-success' : 'bg-danger' }}">{{ strtoupper($hackathon->status) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Descrição:</strong> {{ $hackathon->descricao }}</p>
                            <p><strong>Critérios de Avaliação:</strong> {{ $hackathon->criterios_avaliacao }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">GRUPOS</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>NOME</th>
                                    <th>PARTICIPANTES</th>
                                    <th>STATUS</th>
                                    <th>AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hackathon->grupos as $grupo)
                                <tr>
                                    <td>{{ $grupo->id }}</td>
                                    <td>{{ $grupo->nome }}</td>
                                    <td>
                                        @foreach($grupo->participantes as $participante)
                                            {{ $participante->nome }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="badge {{ $grupo->status == 'Aberto' ? 'bg-success' : 'bg-danger' }}">
                                            {{ strtoupper($grupo->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('grupos.show', $grupo->id) }}" class="btn btn-sm btn-primary">VER</a>
                                        @if($grupo->status == 'Aberto' && !$userInGrupo)
                                            <form action="{{ route('grupos.solicitar', $grupo->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">SOLICITAR</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($hackathon->status == 'Aberto')
                    <div class="mt-3">
                        <a href="{{ route('grupos.create', ['hackathon_id' => $hackathon->id]) }}" class="btn btn-primary">+ NOVO GRUPO</a>
                    </div>
                    @endif
                    
                    @if(auth()->user()->role == 'professor')
                    <hr>
                    <h5 class="mb-3">VALIDAÇÃO HACKATHON</h5>
                    <div class="row">
                        @foreach($hackathon->grupos as $grupo)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h6>{{ $grupo->nome }}</h6>
                                </div>
                                <div class="card-body">
                                    <img src="{{ asset('storage/' . $grupo->imagem) }}" class="img-fluid mb-2" alt="Screenshot">
                                    <p><strong>Data:</strong> {{ date('d/m/Y', strtotime($grupo->created_at)) }}</p>
                                    <a href="{{ route('grupos.validar', $grupo->id) }}" class="btn btn-success w-100">VALIDAR</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection