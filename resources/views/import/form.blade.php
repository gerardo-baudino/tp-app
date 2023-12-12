<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>DAW TP3 - Clientes</title>

    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="jumbotron">
            <h1 class="display-4">Actividad Practica Obligatoria Nro 3</h1>
            <p class="lead">Desarrollo de solución en Laravel Framework</p>
        </div>

        <h4>Importacion de informacion de ventas</h1>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('importar.ventas') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <p>Seleccione el archivo a importar y luego haga click en el boton <b>Procesar</b></p>
                        <div>
                            <input type="file" name="archivo_csv" accept=".csv">
                        </div>
                        <br>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Procesar archivo</button>
                        </div>
                    </form>
                </div>
            </div>

            @if (session('errores'))
            <div class="alert alert-danger">
                <ul>
                    @foreach (session('errores') as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (session('exitos'))
            <div class="alert alert-success">
                <ul>
                    @foreach (session('exitos') as $exito)
                    <li>{{ $exito }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
    </div>

    <footer class="fixed-bottom text-center bg-light p-2">
        <p>Alumno: Baudino Gerardo Luis</p>
    </footer>
</body>

</html>