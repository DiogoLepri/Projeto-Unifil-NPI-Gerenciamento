<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolher Método - UNIFIL</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            background-color: #D2901E;
            color: #000;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 20px;
        }
        
        .container {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .modal-container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        
        .modal-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .option-container {
            margin-bottom: 20px;
        }
        
        .option {
            display: flex;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .option:hover {
            background-color: #f9f9f9;
        }
        
        .option input {
            margin-right: 10px;
        }
        
        .modal-footer {
            padding: 15px 20px;
            text-align: right;
            border-top: 1px solid #eee;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        PROJETO DE EXTENSÃO
    </div>
    
    <div class="container">
        <div class="modal-container">
            <div class="modal-header">
                <h3>Qual desses você irá realizar no Projeto de Extensão?</h3>
            </div>
            
            @if ($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('projetos.salvar-metodo', $projeto->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="option-container">
                        <label class="option">
                            <input type="radio" name="metodo" value="Pensamento Computacional" {{ old('metodo') == 'Pensamento Computacional' ? 'checked' : '' }} required>
                            Pensamento Computacional
                        </label>
                        
                        <label class="option">
                            <input type="radio" name="metodo" value="Criação do App" {{ old('metodo') == 'Criação do App' ? 'checked' : '' }}>
                            Criação do App
                        </label>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">ENVIAR</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>