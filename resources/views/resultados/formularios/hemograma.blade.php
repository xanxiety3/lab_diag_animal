<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resultado Hemograma</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      background: #f5f6f8;
      color: #333;
    }

    /* Header corporativo */
    header {
      background: #27ae60;
      color: white;
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    header h1 {
      margin: 0;
      font-size: 1.2rem;
    }
    .btn-dashboard {
      background: white;
      color: #27ae60;
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: 0.3s;
    }
    .btn-dashboard:hover {
      background: #f0f0f0;
    }

    /* Contenedor principal */
    main {
      max-width: 1200px;
      margin: 30px auto;
      padding: 0 20px;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #27ae60;
    }

    /* Grid de tarjetas */
    .animal-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 20px;
    }

    .animal-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }
    .animal-card:hover {
      transform: translateY(-3px);
    }

    .animal-card legend {
      font-weight: bold;
      color: #27ae60;
      margin-bottom: 10px;
    }

    .form-group {
      margin-bottom: 10px;
    }
    .form-group label {
      display: block;
      font-size: 0.9rem;
      margin-bottom: 5px;
      font-weight: 600;
    }
    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 0.9rem;
    }
    textarea {
      resize: vertical;
    }

    /* Botón guardar */
    .btn-submit {
      display: block;
      margin: 30px auto 0;
      background: #27ae60;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .btn-submit:hover {
      background: #219150;
    }
  </style>
</head>
<body>

  <!-- Header con volver -->
  <header>
    <h1>Resultado Hemograma</h1>
    <button class="btn-dashboard" onclick="window.location.href='/dashboard'">⬅ Volver al Dashboard</button>
  </header>

  <!-- Contenido -->
  <main>
    <h2>Registrar Resultado - Técnica: {{ $tecnica->nombre }}</h2>

    <form action="{{ route('resultados.store_resultado_multiple', ['remisionRecibe' => $remisionRecibeId, 'tecnica' => $tecnica->id]) }}" method="POST">
      @csrf

      <div class="animal-grid">
        @foreach($animales as $animal)
          <fieldset class="animal-card">
            <legend>🐾 {{ $animal->nombre }} (ID: {{ $animal->id }})</legend>

            <input type="hidden" name="animal_id[{{ $animal->id }}]" value="{{ $animal->id }}">

            <div class="form-group">
              <label>Código Interno</label>
              <input type="text" name="codigo_interno[{{ $animal->id }}]" value="{{ old('codigo_interno.'.$animal->id, $animal->codigo_interno ?? '') }}">
            </div>

            <div class="form-group">
              <label>Especie</label>
              <input type="text" name="especie[{{ $animal->id }}]" value="{{ old('especie.'.$animal->id, $animal->especie->nombre ?? '') }}">
            </div>

            <div class="form-group">
              <label>Sexo</label>
              <input type="text" name="sexo[{{ $animal->id }}]" value="{{ old('sexo.'.$animal->id, $animal->sexo->descripcion ?? '') }}">
            </div>

            <div class="form-group">
              <label>HB</label>
              <input type="text" name="hb[{{ $animal->id }}]" value="{{ old('hb.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>HTO</label>
              <input type="text" name="hto[{{ $animal->id }}]" value="{{ old('hto.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>Leucocitos</label>
              <input type="text" name="leucocitos[{{ $animal->id }}]" value="{{ old('leucocitos.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>NEU</label>
              <input type="text" name="neu[{{ $animal->id }}]" value="{{ old('neu.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>EOS</label>
              <input type="text" name="eos[{{ $animal->id }}]" value="{{ old('eos.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>BAS</label>
              <input type="text" name="bas[{{ $animal->id }}]" value="{{ old('bas.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>LIN</label>
              <input type="text" name="lin[{{ $animal->id }}]" value="{{ old('lin.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>MON</label>
              <input type="text" name="mon[{{ $animal->id }}]" value="{{ old('mon.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>Plaquetas</label>
              <input type="text" name="plaquetas[{{ $animal->id }}]" value="{{ old('plaquetas.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>VCM</label>
              <input type="text" name="vcm[{{ $animal->id }}]" value="{{ old('vcm.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>HCM</label>
              <input type="text" name="hcm[{{ $animal->id }}]" value="{{ old('hcm.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>CHCM</label>
              <input type="text" name="chcm[{{ $animal->id }}]" value="{{ old('chcm.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>Hemoparásitos</label>
              <input type="text" name="hemoparasitos[{{ $animal->id }}]" value="{{ old('hemoparasitos.'.$animal->id) }}">
            </div>

            <div class="form-group">
              <label>Observaciones</label>
              <textarea name="observaciones[{{ $animal->id }}]">{{ old('observaciones.'.$animal->id) }}</textarea>
            </div>
          </fieldset>
        @endforeach
      </div>

      <button type="submit" class="btn-submit">💾 Guardar Resultados</button>
    </form>
  </main>

</body>
</html>
