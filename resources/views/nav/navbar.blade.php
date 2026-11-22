<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Practicas Kenneth</title>
    @vite(['resources/js/app.js'])
</head>
<body class="bg-light">

    @auth
        <nav class="navbar navbar-dark bg-dark mb-4">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">Prácticas Laravel</a>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth

    <div class="container mt-4">
        <x-alertas />
        @yield('contenido')
    </div>

</body>
</html>