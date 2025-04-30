<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Portal UNIFIL</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
        }
        .container {
            display: flex;
            height: 100%;
        }
        .card {
            width: 50%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .card:hover {
            filter: brightness(1.1);
        }
        .hackathon-card {
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset("img/hach.png") }}');
            background-size: cover;
            background-position: center;
            color: white;
        }
        .extensao-card {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset("img/extensao.jpeg") }}');
            background-size: cover;
            background-position: center;
            color: #fff;
        }
        .card-content {
            text-align: center;
            padding: 20px;
            max-width: 80%;
        }
        .card-title {
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .icon-container {
            margin-bottom: 30px;
        }
        .icon-container img {
            width: 150px;
            height: 150px;
            object-fit: contain;
        }
        .extensao-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .gear-icon {
            width: 50px;
            height: 50px;
            margin: 10px;
            opacity: 0.7;
        }
        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: rgba(0,0,0,0.5);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .logout-button:hover {
            background-color: rgba(0,0,0,0.7);
        }
        .user-info {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            background-color: rgba(0,0,0,0.5);
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
        }
        .logo img {
            height: 60px;
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="logout-button">Sair</button>
    </form>
    <div class="user-info">
        Olá, {{ Auth::user()->name }}
    </div>
    <div class="logo">
        <img src="{{ asset('img/logo-computacao.png') }}" alt="Logo Computação UNIFIL">
    </div>
    <div class="container">
        <a href="{{ route('hackathons.index') }}" class="card hackathon-card">
            <div class="card-content">
                <h2 class="card-title">HACKATHON</h2>
                <div class="icon-container">
                    <img src="{{ asset('img/hach.png') }}" alt="Hackathon">
                </div>
            </div>
        </a>
        <a href="{{ route('projetos.index') }}" class="card extensao-card">
            <div class="card-content">
                <h2 class="card-title">PROJETOS DE EXTENSÃO</h2>
                <div class="extensao-content">
                    <img src="{{ asset('img/extensao.jpeg') }}" alt="Projetos de Extensão" style="width: 200px; height: auto; margin-bottom: 20px;">
                </div>
            </div>
        </a>
    </div>
</body>
</html>