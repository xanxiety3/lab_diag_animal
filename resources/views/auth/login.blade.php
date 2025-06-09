<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <!-- Imagen: puedes reemplazar la ruta por la tuya -->
            <img src="{{ asset('img/logoSinfondo.png') }}" alt="Login Image">
        </div>
        <div class="form-section">
            <h2>Bienvenido</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label for="email">Correo electrónico</label>
                <input type="email" name="email" required autofocus>

                <label for="password">Contraseña</label>
                <input type="password" name="password" required>

                <button type="submit">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</body>
</html>












