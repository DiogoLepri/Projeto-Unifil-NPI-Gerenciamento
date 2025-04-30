@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" style="background-color: #D2901E; color: white;">
                    <h5 class="mb-0">CRIAÇÃO HACKATHON</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hackathons.store') }}">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control" name="nome" placeholder="Nome" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="date" class="form-control" name="data_inicio" placeholder="Data Início" required>
                                    <span class="input-group-text">até</span>
                                    <input type="date" class="form-control" name="data_fim" placeholder="Data Fim" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="local" placeholder="Local" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <textarea class="form-control" name="descricao" rows="3" placeholder="Descrição" required></textarea>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <textarea class="form-control" name="criterios_avaliacao" rows="3" placeholder="Critérios de Avaliação" required></textarea>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <select class="form-select" name="status" required>
                                    <option value="" selected disabled>Status</option>
                                    <option value="Aberto">ABERTO</option>
                                    <option value="Fechado">FECHADO</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('hackathons.index') }}" class="btn btn-secondary me-2">CANCELAR</a>
                                <button type="submit" class="btn btn-success">CRIAR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection