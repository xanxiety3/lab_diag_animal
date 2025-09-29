<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <link rel="stylesheet" href="{{ asset('css/remision_envio.css') }}">
  <title>Registro de Remisión</title>
</head>

<body>
  <header class="header">
    <div class="logo-container">
      <img src="{{ asset('img/logoSinfondo.png') }}" alt="Logo">
      <h1>Laboratorio Clínico Veterinario</h1>
    </div>
    <nav>
      <ul class="nav-links">
        <li><a href="#">Inicio</a></li>
        <li><a href="#">Remisiones</a></li>
        <li><a href="#">Pacientes</a></li>
        <li><a href="#">Cerrar sesión</a></li>
      </ul>
    </nav>
  </header>

  <main class="main-content">
    <form method="POST" action="{{ route('remisiones.store') }}" class="remision-form">
      @csrf

      <div class="form-section">
        <div class="form-group">
          <label for="fecha">Fecha</label>
          <input type="date" id="fecha" name="fecha" required value="{{ date('Y-m-d') }}">
        </div>
<div class="form-group">
  <label for="cliente_id">Cliente</label>
  <input type="hidden" name="cliente_id" value="{{ $selectedCliente }}">
  <input type="text" value="{{ $clientes->find($selectedCliente)?->nombre }}" readonly>
</div>


</div>


        <div class="form-group full-width">
          <label id="observaciones1" for="observaciones">Observaciones generales</label>
          <textarea id="observaciones" name="observaciones" ></textarea>
        </div>
      </div>

   <!-- TIPOS DE MUESTRA -->
<div class="samples-section">
    <h4>Tipos de muestra</h4>

    <div class="samples-grid">
        @foreach ($tiposMuestra as $tipo)
            <div class="sample-box">
                <div class="form-group checkbox">
                    <input type="checkbox" id="tipo_{{ $tipo->id }}" name="tipos_muestra[{{ $tipo->id }}][activo]" value="1">
                    <label for="tipo_{{ $tipo->id }}"><strong>{{ $tipo->nombre }}</strong></label>
                </div>

                <div class="form-group">
                    <label for="cantidad_{{ $tipo->id }}">Cantidad</label>
                    <input type="number" name="tipos_muestra[{{ $tipo->id }}][cantidad]" id="cantidad_{{ $tipo->id }}" min="1">
                </div>

                <div class="form-group">
                    <label for="refrigeracion_{{ $tipo->id }}">Refrigeración</label>
                    <select name="tipos_muestra[{{ $tipo->id }}][refrigeracion]" id="refrigeracion_{{ $tipo->id }}">
                        <option value="">--</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="obs_{{ $tipo->id }}">Observaciones</label>
                    <textarea name="tipos_muestra[{{ $tipo->id }}][observaciones]" id="obs_{{ $tipo->id }}"></textarea>
                </div>
            </div>
        @endforeach
    </div>
</div>


      <div class="form-actions">
        <button type="submit" class="submit-btn">Guardar remisión</button>
      </div>
    </form>
  </main>
</body>
</html>


{{----}}
