@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background-color: #D2901E; color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">HACKATHONS ATUAIS</h5>
                        @if(auth()->user()->role == 'professor')
                        <a href="{{ route('hackathons.create') }}" class="btn btn-sm btn-success">+ CRIAR</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Buscar hackathons...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px">ID</th>
                                    <th>NOME</th>
                                    <th>DATA</th>
                                    <th>LOCAL</th>
                                    <th style="width: 120px">STATUS</th>
                                    <th style="width: 120px">AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hackathons as $hackathon)
                                <tr>
                                    <td>{{ $hackathon->id }}</td>
                                    <td>{{ $hackathon->nome }}</td>
                                    <td>{{ date('d/m/Y', strtotime($hackathon->data_inicio)) }} - {{ date('d/m/Y', strtotime($hackathon->data_fim)) }}</td>
                                    <td>{{ $hackathon->local }}</td>
                                    <td>
                                        <span class="badge {{ $hackathon->status == 'Aberto' ? 'bg-success' : 'bg-danger' }}">
                                            {{ strtoupper($hackathon->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('hackathons.show', $hackathon->id) }}" class="btn btn-sm btn-primary">VER</a>
                                        @if($hackathon->status == 'Aberto')
                                            <a href="{{ route('grupos.index', ['hackathon_id' => $hackathon->id]) }}" class="btn btn-sm btn-success">ENTRAR</a>
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
    // Função para filtrar hackathons
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
</script>
@endpush
@endsection