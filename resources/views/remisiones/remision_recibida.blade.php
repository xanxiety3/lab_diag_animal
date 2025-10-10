<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📋 Recepción de Muestra</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f7fafc;
      margin: 0;
      color: #333;
    }

    header {
      background: #27ae60;
      color: #fff;
      padding: 14px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    header h1 {
      font-size: 1.2rem;
      margin: 0;
    }

    header .btn-back {
      background: #fff;
      color: #27ae60;
      padding: 6px 12px;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: 0.3s;
    }

    header .btn-back:hover {
      background: #e8f5e9;
    }

    main {
      max-width: 950px;
      margin: 30px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      padding: 25px 40px;
    }

    h2, h3 {
      color: #27ae60;
      border-bottom: 2px solid #27ae60;
      padding-bottom: 4px;
      margin-top: 25px;
    }

    .tecnica-card {
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 15px;
      margin: 15px 0;
      background: #f9f9f9;
      transition: 0.3s;
    }

    .tecnica-card:hover {
      background: #ecfdf3;
    }

    .tecnica-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
    }

    .form-control {
      width: 80px;
      padding: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .btn-animales, .btn-submit {
      background: #27ae60;
      color: white;
      border: none;
      border-radius: 6px;
      padding: 8px 14px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-animales:hover, .btn-submit:hover {
      background: #219150;
    }

    .btn-submit {
      display: block;
      margin: 30px auto 0;
      font-size: 1rem;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 420px;
      max-height: 80vh;
      overflow-y: auto;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .close-btn {
      float: right;
      cursor: pointer;
      color: red;
      font-weight: bold;
    }

    label {
      display: block;
      margin: 5px 0;
    }
  </style>
</head>
<body>

<header>
  <h1>📋 Recepción de Muestra - Remisión #{{ $remision->id }}</h1>
  <a href="{{ route('dashboard') }}"><button class="btn-back">🏠 Volver</button></a>
</header>

<main>
  <form method="POST" action="{{ route('remisiones.recibida') }}">
    @csrf
    <input type="hidden" name="muestra_enviada_id" value="{{ $remision->id }}">

    @foreach ($tecnicas->groupBy('tipo_muestra_id') as $tipoId => $grupo)
      <h3>{{ $grupo->first()->tipos_muestra->nombre }}</h3>

      @foreach ($grupo as $tecnica)
        <div class="tecnica-card">
          <div class="tecnica-header">
            <label>
              <input type="checkbox" name="tecnicas[{{ $tecnica->id }}][id]" value="{{ $tecnica->id }}">
              <strong>{{ $tecnica->nombre }} ({{ $tecnica->sigla ?? '—' }})</strong>
            </label>
            <span>💰 ${{ number_format($tecnica->valor_unitario, 0, ',', '.') }}</span>
          </div>

          <div style="margin-top:10px;">
            Cantidad:
            <input type="number" name="tecnicas[{{ $tecnica->id }}][cantidad]" class="form-control cantidad" min="0" value="0">
            <button type="button" class="btn-animales" onclick="abrirModal({{ $tecnica->id }})">🐾 Asignar animales</button>
          </div>

          <div id="contenedor-animales-{{ $tecnica->id }}"></div>
        </div>
      @endforeach
    @endforeach

    <button type="submit" class="btn-submit">💾 Guardar Recepción</button>
    @if ($errors->any())
  <div style="background:#ffe0e0;color:#b00;padding:10px;border-radius:6px;margin-bottom:15px;">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif

  </form>
</main>

<!-- MODAL DE ASIGNACIÓN -->
<div id="modal-animales" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="cerrarModal()">✖</span>
    <h3>Seleccionar animales</h3>
    <label><input type="checkbox" id="select-todos" onchange="toggleTodos()"> Seleccionar todos</label>
    <hr>
    <div id="lista-animales">
      @foreach ($remision->persona->animales as $animal)
        <label>
          <input type="checkbox" class="chk-animal" value="{{ $animal->id }}">
          🐾 {{ $animal->nombre }} ({{ $animal->especie->nombre ?? '—' }})
        </label>
      @endforeach
    </div>
    <button type="button" class="btn-animales" onclick="guardarAnimales()">✔ Asignar</button>
  </div>
</div>

<script>
  let tecnicaActual = null;

  function abrirModal(tecnicaId) {
    tecnicaActual = tecnicaId;
    document.getElementById('modal-animales').style.display = 'flex';
  }

  function cerrarModal() {
    document.getElementById('modal-animales').style.display = 'none';
  }

  function toggleTodos() {
    const checked = document.getElementById('select-todos').checked;
    document.querySelectorAll('.chk-animal').forEach(c => c.checked = checked);
  }

  function guardarAnimales() {
    if (!tecnicaActual) return;

    const seleccionados = Array.from(document.querySelectorAll('.chk-animal:checked')).map(c => c.value);
    const contenedor = document.getElementById(`contenedor-animales-${tecnicaActual}`);
    const cantidadInput = document.querySelector(`input[name='tecnicas[${tecnicaActual}][cantidad]']`);
    const checkboxPrincipal = document.querySelector(`input[name='tecnicas[${tecnicaActual}][id]']`);

    contenedor.innerHTML = '';

    seleccionados.forEach(id => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = `tecnicas[${tecnicaActual}][animales][]`;
      input.value = id;
      contenedor.appendChild(input);
    });

    cantidadInput.value = seleccionados.length;

    if (seleccionados.length > 0) {
      checkboxPrincipal.checked = true;
    } else {
      checkboxPrincipal.checked = false;
      cantidadInput.value = 0;
    }

    cerrarModal();
  }

  // Cerrar modal al hacer clic fuera
  window.onclick = function(e) {
    const modal = document.getElementById('modal-animales');
    if (e.target === modal) cerrarModal();
  };
</script>

</body>
</html>
