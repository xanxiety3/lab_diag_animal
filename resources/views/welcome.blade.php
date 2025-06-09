<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body>
    <h1>
        @if(Auth::user()->rol_id == 1)
            ¡Hola Admin!
        @else
            ¡Hola Usuario!
        @endif
    </h1>

    <p>Bienvenido al sistema de laboratorio de diagnóstico animal.</p>

    <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Cerrar sesión</button>
</form>

</body>
</html>
