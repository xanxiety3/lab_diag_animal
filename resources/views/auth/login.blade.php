<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-wrapper">
        <!-- Sección izquierda: imagen / branding -->
        <div class="login-image">
            <img src="{{ asset('img/logoSinfondo.png') }}" alt="Logo">
            <h1>Lab Animal</h1>
            <p>Diagnóstico Veterinario Profesional</p>
        </div>

        <!-- Sección derecha: formulario -->
        <div class="login-form">
            <h2>Bienvenido</h2>

            @if(session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group">
                    <input type="email" name="email" required autofocus>
                    <label>Correo Electrónico</label>
                </div>

                <div class="input-group">
                    <input type="password" name="password" required>
                    <label>Contraseña</label>
                </div>

                <button type="submit">Iniciar Sesión</button>

            </form>
        </div>
    </div>
</body>
</html>




