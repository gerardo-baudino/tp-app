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

        <h4>Gestión de Clientes</h1>

        <div class="card">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <!-- Mostrar datos de los clientes encontrados -->
                    <p>Listado de clientes:</h2>
                    @if(isset($clientes) && $clientes->count() > 0)
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nro Cliente</th>
                                <th>CUIT Cliente</th>
                                <th>Razón Social</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->id }}</td>
                                <td>{{ $cliente->nro_cliente }}</td>
                                <td>{{ $cliente->cuit_cliente }}</td>
                                <td>{{ $cliente->razon_social }}</td>
                                <td>
                                    <form action="{{ route('cliente.buscar') }}" method="GET">
                                        @csrf
                                        <!-- Campo oculto para pasar el CUIT de cliente -->
                                        <input type="hidden" name="cuit_cliente" value="{{ $cliente->cuit_cliente }}">
                                        <button type="submit" class="btn btn-primary">Editar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-info">
                        No se encontraron clientes cargados en la base de datos
                    </div>
                    @endif
                </li>
                <li class="list-group-item">
                    @if(isset($clienteEncontrado))
                    <!-- Formulario para modificar un cliente -->
                    <p>Modificar cliente:</p>
                    <form action="{{ route('cliente.actualizar', $clienteEncontrado->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nro_cliente">Nro Cliente:</label>
                            <input type="text" class="form-control" name="nro_cliente" id="nro_cliente" maxlength="11" pattern="\d+" value="{{ $clienteEncontrado->nro_cliente }}" required>
                        </div>

                        <div class="form-group">
                            <label for="cuit_cliente">CUIT:</label>
                            <input type="text" class="form-control" name="cuit_cliente" id="cuit_cliente" maxlength="11" pattern="\d+" title="Solo se permiten números" value="{{ $clienteEncontrado->cuit_cliente }}" required>
                        </div>

                        <div class="form-group">
                            <label for="razon_social">Razón Social:</label>
                            <input type="text" class="form-control" name="razon_social" value="{{ $clienteEncontrado->razon_social }}" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Actualizar Datos</button>
                        </div>
                    </form>
                    @else
                    <!-- Formulario para crear un nuevo cliente -->
                    <p>Cargar nuevo cliente:</p>
                    <form action="{{ route('cliente.crear') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nro_cliente">Nro Cliente:</label>
                            <input type="text" class="form-control" name="nro_cliente" id="nro_cliente" maxlength="11" pattern="\d+" required>
                        </div>

                        <div class="form-group">
                            <label for="cuit_cliente">CUIT:</label>
                            <input type="text" class="form-control" name="cuit_cliente" id="cuit_cliente" maxlength="11" pattern="\d+" title="Solo se permiten números" required>
                        </div>

                        <div class="form-group">
                            <label for="razon_social">Razón Social:</label>
                            <input type="text" class="form-control" name="razon_social" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Crear</button>
                        </div>
                    </form>
                    @endif
                </li>
            </ul>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

    </div>

    <footer class="fixed-bottom text-center bg-light p-2">
        <p>Alumno: Baudino Gerardo Luis</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        // Función para formatear el CUIT en tiempo real
        $(document).ready(function() {
            $('#cuit_cliente').on('input', function() {
                // Eliminar cualquier carácter que no sea un número
                var sanitized = $(this).val().replace(/[^0-9]/g, '');

                // Formatear el CUIT en el formato deseado
                if (sanitized.length == 11) {
                    var formatted = sanitized.substring(0, 2) + sanitized.substring(2, 10) + sanitized.substring(10);
                    $(this).val(formatted);
                } else {
                    $(this).val(sanitized);
                }
            });
            $('#nro_cliente').on('input', function() {
                // Eliminar cualquier carácter que no sea un número
                var sanitized = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(sanitized);
            });
        });
    </script>
</body>

</html>