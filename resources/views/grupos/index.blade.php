@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #D2901E; color: white;">
                    <h5 class="mb-0">HACKATHON {{ strtoupper($hackathon->nome) }} - GRUPOS</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Buscar grupos...">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('grupos.create', ['hackathon_id' => $hackathon->id]) }}" class="btn btn-primary">+ NOVO GRUPO</a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="cursor: pointer" onclick="sortTable(0)">
                                        ID <i class="bi bi-arrow-down-up"></i>
                                    </th>
                                    <th style="cursor: pointer" onclick="sortTable(1)">
                                        NOME <i class="bi bi-arrow-down-up"></i>
                                    </th>
                                    <th style="cursor: pointer" onclick="sortTable(2)">
                                        PARTICIPANTES <i class="bi bi-arrow-down-up"></i>
                                    </th>
                                    <th style="cursor: pointer" onclick="sortTable(3)">
                                        STATUS <i class="bi bi-arrow-down-up"></i>
                                    </th>
                                    <th>AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grupos as $grupo)
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
                                        
                                        @if($grupo->status == 'Aberto')
                                            @if($userInGrupo)
                                                @if($userIsGrupoLeader)
                                                    <a href="{{ route('grupos.edit', $grupo->id) }}" class="btn btn-sm btn-warning">EDITAR</a>
                                                @else
                                                    <form action="{{ route('grupos.sair', $grupo->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger">SAIR</button>
                                                    </form>
                                                @endif
                                            @else
                                                <form action="{{ route('grupos.solicitar', $grupo->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">SOLICITAR</button>
                                                </form>
                                            @endif
                                        @endif
                                        
                                        @if(auth()->user()->role == 'professor')
                                            <form action="{{ route('grupos.destroy', $grupo->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este grupo?')">REMOVER</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Função para filtrar a tabela
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            let found = false;
            const cells = row.querySelectorAll('td');
            
            cells.forEach(cell => {
                if (cell.textContent.toLowerCase().includes(searchText)) {
                    found = true;
                }
            });
            
            row.style.display = found ? '' : 'none';
        });
    });
    
    // Função para ordenar a tabela
    function sortTable(n) {
        let table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.querySelector('table');
        switching = true;
        dir = "asc";
        
        while (switching) {
            switching = false;
            rows = table.rows;
            
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
</script>
@endpush
@endsection