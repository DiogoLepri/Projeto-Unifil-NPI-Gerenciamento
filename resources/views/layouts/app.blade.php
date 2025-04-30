<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UNIFIL-PROJETOS') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f5f5f5;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .nav-link {
            color: #333;
            font-weight: 600;
        }
        .nav-link.active {
            color: #D2901E !important;
            border-bottom: 2px solid #D2901E;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
            font-weight: bold;
            text-transform: uppercase;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .bg-orange {
            background-color: #D2901E;
            color: white;
        }
        .user-welcome {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            background-color: #343a40;
            color: white;
        }
        .user-welcome .btn {
            margin-left: 10px;
            background-color: #6c757d;
            border: none;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- User Welcome Bar -->
        <div class="user-welcome">
            <div class="container d-flex justify-content-between align-items-center">
                <div>
                    @auth
                        Olá, {{ Auth::user()->name }}
                    @else
                        Bem-vindo
                    @endauth
                </div>
                <div>
                    @auth
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm">
                            <i class="bi bi-person"></i> Perfil
                        </a>
                        <a href="{{ route('logout') }}" class="btn btn-sm" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-sm">Cadastro</a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Main Navigation Bar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo-computacao.png') }}" alt="UNIFIL Computação" height="30">
                    UNIFIL-PROJETOS
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('projetos*') ? 'active' : '' }}" href="{{ route('projetos.index') }}">
                                    <i class="bi bi-journal-text"></i> Projetos de Extensão
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('hackathons*') ? 'active' : '' }}" href="{{ route('hackathons.index') }}">
                                    <i class="bi bi-code-slash"></i> Hackathons
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('profile*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-circle"></i> Minha Conta
                                </a>
                            </li>
                            @if(Auth::user()->role == 'professor')
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('usuarios*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                                    <i class="bi bi-people"></i> Usuários
                                </a>
                            </li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>