@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" style="background-color: #D2901E; color: white;">
                    <h5 class="mb-0">CRIAR NOVO GRUPO</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('grupos.store') }}">
                        @csrf
                        <input type="hidden" name="hackathon_id" value="{{ $hackathon->id }}">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hackathon" class="form-label">Hackathon</label>
                                <input type="text" class="form-control" id="hackathon" value="{{ $hackathon->nome }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome do Grupo</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Selecione os Participantes</label>
                                <div class="card">
                                    <div class="card-body">
                                        <p class="text-muted mb-2">Você será automaticamente adicionado como líder do grupo</p>
                                        
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-search"></i>
                                            </span>
                                            <input type="text" class="form-control" id="participanteSearch" placeholder="Buscar participantes...">
                                        </div>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 50px">Selecionar</th>
                                                        <th>Nome</th>
                                                        <th>Curso</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($usuarios as $usuario)
                                                        @if($usuario->id != auth()->id())
                                                        <tr class="participante-row">
                                                            <td>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="participantes[]" value="{{ $usuario->id }}" id="check-{{ $usuario->id }}">
                                                                </div>
                                                            </td>
                                                            <td>{{ $usuario->nome }}</td>
                                                            <td>{{ $usuario->curso }}</td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('grupos.index', ['hackathon_id' => $hackathon->id]) }}" class="btn btn-secondary me-2">CANCELAR</a>
                                <button type="submit" class="btn btn-success">CRIAR GRUPO</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Filtrar participantes
    document.getElementById('participanteSearch').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('.participante-row');
        
        rows.forEach(row => {
            const nome = row.querySelectorAll('td')[1].textContent.toLowerCase();
            const curso = row.querySelectorAll('td')[2].textContent.toLowerCase();
            
            if (nome.includes(searchText) || curso.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection