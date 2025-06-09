<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Cliente, Animal y Dirección</title>
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">
    
</head>
<body >
    
<header class="main-header">
    <div class="logo-container">
        <img src="{{ asset('img/logoSinfondo.png') }}" alt="Logo" class="logo">
        <h1 class="title">Veterinaria Registro</h1>
    </div>
    <nav class="nav-links">
        <a href="#">Inicio</a>
        <a href="#">Registros</a>
        <a href="#">Configuración</a>
        <a href="#">Cerrar sesión</a>
    </nav>
</header>
<main class="form-wrapper">
    <section class="form-section">
            <div class="container mt-5">
                <h2 class="mb-4">Registro de Cliente, Animal y Dirección</h2>

                {{-- Mensajes de error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Paso 1: Persona --}}
                @if ($step === 'persona')
                <div id="paso-persona" class="form-step active">
                    <form method="POST" action="{{ route('registro.persona.guardar') }}">
                        @csrf
                        <h4 class="mb-3">1. Datos de la Persona</h4>

                        <div class="mb-3">
                            <label for="tipo_documento_id" class="form-label">Tipo De Documento</label>
                            <select name="tipo_documento_id" id="tipo_documento_id" class="form-select" required>
                                <option value="" disabled selected>Seleccione un tipo de documento</option>
                                @foreach ($tipoDoc as $tipo)
                                    <option value="{{ $tipo->id }}" {{ old('tipo_documento_id') == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_documento_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Número de Documento</label>
                            <input name="numero_documento" class="form-control" required value="{{ old('numero_documento') }}">
                        </div>
                        <div class="mb-3">
                            <label>Nombres</label>
                            <input name="nombres" class="form-control" required value="{{ old('nombres') }}">
                        </div>
                        <div class="mb-3">
                            <label>Apellidos</label>
                            <input name="apellidos" class="form-control" required value="{{ old('apellidos') }}">
                        </div>
                        <div class="mb-3">
                            <label>Correo</label>
                            <input type="email" name="correo" class="form-control" required value="{{ old('correo') }}">
                        </div>
                        <div class="mb-3">
                            <label>Teléfono</label>
                            <input name="telefono" class="form-control" required value="{{ old('telefono') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar y continuar</button>
                    </form>
                </div>
                @endif

                            {{-- Paso 2: Animales --}}
                            @if ($step === 'animales')
                            <div id="paso-animal" class="form-step active">
                                <form method="POST" action="{{ route('registro.animales.guardar') }}">
                                    @csrf
                                    <h4 class="mb-3">2. Datos del Animal</h4>

                                    <div id="animales-wrapper">
                                        <div class="animal-item border p-3 mb-3">
                                <div class="animal-row">
                            <select name="animales[0][especie_id]" class="especie-select" required>
                                <option value="" disabled selected>Seleccione especie</option>
                                @foreach ($especies as $especie)
                                    <option value="{{ $especie->id }}">{{ $especie->nombre }}</option>
                                @endforeach
                            </select>

                            <select name="animales[0][raza_id]" class="raza-select" required>
                                <option value="" disabled selected>Seleccione raza</option>
                            </select>
                        </div>
                                        


                                <div class="mb-2">
                                    <label>Sexo</label>
                                    <select name="animales[0][sexo_id]" class="form-select" required>
                                        <option value="" disabled selected>Seleccione sexo</option>
                                        @foreach ($sexos as $sexo)
                                            <option value="{{ $sexo->id }}" {{ old('animales.0.sexo_id') == $sexo->id ? 'selected' : '' }}>
                                                {{ $sexo->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label>Edad</label>
                                    <input type="number" name="animales[0][edad]" class="form-control" required value="{{ old('animales.0.edad') }}">
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary mb-3" onclick="agregarAnimal()">+ Añadir otro animal</button>
                        <br>
                        <button type="submit" class="btn btn-primary">Guardar y continuar</button>
                    </form>
                </div>
                @endif

                {{-- Paso 3: Dirección --}}
                @if ($step === 'direccion')
                <div id="paso-direccion" class="form-step active">
                    <form method="POST" action="{{ route('registro.direccion.guardar') }}">
                        @csrf
                        <h4 class="mb-3">3. Dirección</h4>
                            <div class="mb-3">
                                <label>Departamento</label>
                                <select name="departamento_id" id="departamento-select" class="form-select" required>
                                    <option value="" disabled selected>Seleccione departamento</option>
                                    @foreach ($departamentos as $dpto)
                                        <option value="{{ $dpto->id }}">{{ $dpto->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Municipio</label>
                                <select name="municipio_id" id="municipio-select" class="form-select" required>
                                    <option value="" disabled selected>Seleccione municipio</option>
                                    {{-- Se llenará dinámicamente --}}
                                </select>
                            </div>
                    
            <div class="mb-3">
                <label>Tipo de Dirección</label>
                <select name="tipo_direccion_id" class="form-select" required>
                    <option value="" disabled selected>Seleccione tipo de dirección</option>
                    @foreach ($tiposDireccion as $tipoDireccion)
                        <option value="{{ $tipoDireccion->id }}" {{ old('tipo_direccion_id') == $tipoDireccion->id ? 'selected' : '' }}>
                            {{ $tipoDireccion->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Tipo de Ubicación</label>
                <select name="tipo_ubicacion_id" class="form-select" required>
                    <option value="" disabled selected>Seleccione tipo de ubicación</option>
                    @foreach ($tiposUbicacion as $tipoUbicacion)
                        <option value="{{ $tipoUbicacion->id }}" {{ old('tipo_ubicacion_id') == $tipoUbicacion->id ? 'selected' : '' }}>
                            {{ $tipoUbicacion->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Dirección Detallada</label>
                <input name="direccion_detallada" class="form-control" required value="{{ old('direccion_detallada') }}">
            </div>


                        <button type="submit" class="btn btn-success">Finalizar Registro</button>
                    </form>
                </div>
                @endif

                {{-- Paso 4: Completado --}}
                @if ($step === 'completado')
                <div class="form-step active">
                    <div class="alert alert-success">
                        <h5>✅ Registro completado exitosamente.</h5>
                    </div>
                    <a href="{{ route('registro.wizard', ['step' => 'persona']) }}" class="btn btn-primary">Registrar otro</a>
                </div>
                @endif
            </div>
    </section>
</main>
<script src="{{ asset('js/wizard.js') }}"></script>
</body>
</html>
