@extends('layouts.app')

@section('content')
<!-- In the header or hero section -->
<div class="text-center mb-4">
    <img src="{{ asset('img/logo-computacao.png') }}" alt="UNIFIL Computação" class="img-fluid" style="max-height: 100px;">
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-orange">
                    <h5 class="mb-0">HACKATHONS</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('img/hach.png') }}" alt="Hackathon" class="img-fluid mb-3" style="max-height: 200px;">
                    <p class="mb-4">Participe de desafios de programação em equipe, desenvolva soluções inovadoras e ganhe experiência prática.</p>
                    <a href="{{ route('hackathons.index') }}" class="btn btn-primary">VER HACKATHONS</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-orange">
                    <h5 class="mb-0">PROJETOS DE EXTENSÃO</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('img/extensao.jpeg') }}" alt="Projetos de Extensão" class="img-fluid mb-3" style="max-height: 200px;">
                    <p class="mb-4">Aplique seu conhecimento em projetos reais que beneficiam a comunidade e enriquecem seu currículo acadêmico.</p>
                    <a href="{{ route('projetos.index') }}" class="btn btn-primary">VER PROJETOS</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-orange">
                    <h5 class="mb-0">SOBRE O SISTEMA</h5>
                </div>
                <div class="card-body">
                    <p>Bem-vindo ao sistema de gerenciamento de projetos de extensão e hackathons da Computação UNIFIL.</p>
                    <p>Este sistema foi desenvolvido para facilitar a participação dos alunos em atividades extracurriculares,
                    permitindo que você se inscreva em projetos de extensão e forme grupos para hackathons de forma simples e organizada.</p>
                    <h5 class="mt-4">Como utilizar:</h5>
                    <ul>
                        <li>Acesse <strong>Hackathons</strong> para ver os eventos disponíveis e formar grupos</li>
                        <li>Acesse <strong>Projetos de Extensão</strong> para conhecer os projetos oferecidos e solicitar participação</li>
                        <li>Professores podem gerenciar os projetos, hackathons e validar a participação dos alunos</li>
                    </ul>
                    @guest
                    <div class="mt-4 text-center">
                        <a href="{{ route('login') }}" class="btn btn-primary me-3">LOGIN</a>
                        <a href="{{ route('register') }}" class="btn btn-secondary">CADASTRO</a>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>

<!-- In the footer section -->
<div class="container">
    <div class="row mt-5 pt-4 border-top">
        <div class="col-md-4 text-center">
            <img src="{{ asset('img/logo-unifil.png') }}" alt="UNIFIL" class="img-fluid" style="max-height: 80px;">
        </div>
        <div class="col-md-4 text-center">
            <p>© {{ date('Y') }} UNIFIL Computação - Todos os direitos reservados</p>
        </div>
        <div class="col-md-4 text-center">
            <img src="{{ asset('img/logo-npi.png') }}" alt="NPI UNIFIL" class="img-fluid" style="max-height: 80px;">
        </div>
    </div>
</div>
@endsection