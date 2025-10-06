<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Laboratorio Diagnóstico Animal</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
        <img src="{{ asset('img/logoSinfondo.png') }}" alt="Logo Laboratorio" class="logo">
        <h1>Laboratorio Diagnóstico Animal</h1>
        <p>Crea una cuenta para continuar</p>
        <!-- Botón Volver -->
        <a href="{{ route('dashboard') }}" class="btn-back">← Volver al Dashboard</a>
    </div>

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <div class="input-group">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                </div>

                <div class="input-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                </div>

                <div class="input-group">
                    <label for="numero_documento">Numero de documento</label>
                    <input type="text" name="numero_documento" id="numero_documento" value="{{ old('numero_documento') }}" required>
                </div>

                <div class="input-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" required>
                </div>

                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="input-group">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>

                <div class="input-group">
                    <label for="rol_id">Rol</label>
                    <select name="rol_id" id="rol_id" required>
                        <option value="">Seleccione un rol</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                                {{ $rol->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-submit">Registrarse</button>
            </form>

            
        </div>
    </div>
</body>
</html>
