<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Laboratorio Diagnóstico Animal</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <body>
    <div>
        <div class="auth-container">
            <h1>Laboratorio Diagnóstico Animal</h1>
            <p>Crea una cuenta para continuar</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <!-- Campos del formulario -->
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>

                <label for="email">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>

                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required>

                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>

                <label for="rol_id">Rol</label>
                <select name="rol_id" id="rol_id" required>
                    <option value="">Seleccione un rol</option>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">Registrarse</button>
            </form>
            <br>
            <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
        </div>

        <!-- Footer centrado -->
        <p class="footer">© 2025 Laboratorio Diagnóstico Animal</p>
    </div>
</body>

</body>
</html>
