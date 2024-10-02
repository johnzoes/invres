<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reservas</title>
    <link rel="stylesheet" href="{{ asset(path: 'css/app.css') }}">
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('reservas.index') }}">Reservas</a>
            <a href="{{ route('items.index') }}">Ítems</a>
            <a href="{{ route('asistentes.index') }}">Asistentes</a>
            <a href="{{ route( 'notificaciones.index') }}">Notificaciones</a>
            <a href="{{ route( 'usuarios.index') }}">Usuarios</a>

        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2024 Sistema de Reservas</p>
    </footer>
</body>
</html>
