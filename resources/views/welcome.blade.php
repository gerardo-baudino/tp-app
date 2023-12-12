<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>DAW TP3</title>

    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="jumbotron">
            <h1 class="display-4">Actividad Practica Obligatoria Nro 3</h1>
            <p class="lead">Desarrollo de solución en Laravel Framework</p>
        </div>

        <h4>Bienvenido</h1>
        <p>Selecciona una opción:</p>
        <ul class="list-group">
            <li class="list-group-item"><a href="{{ route('importar.ventas') }}">Importar Ventas</a></li>
            <li class="list-group-item"><a href="{{ route('cliente.todos') }}">Clientes</a></li>
        </ul>
    </div>

    <footer class="fixed-bottom text-center bg-light p-2">
        <p>Alumno: Baudino Gerardo Luis</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>