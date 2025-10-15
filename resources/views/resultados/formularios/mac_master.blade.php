<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resultado Coproparasitológico</title>
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

    /* Grid tarjetas */
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
      margin-bottom: 15px;
      font-size: 1rem;
    }

    /* Distribución de inputs */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }
    .form-group label {
      font-size: 0.9rem;
      margin-bottom: 5px;
      font-weight: 600;
    }
    .form-group input,
    .form-group textarea {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 0.9rem;
    }
    textarea {
      resize: vertical;
      min-height: 60px;
      grid-column: span 2; /* textarea ocupa todo el ancho */
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

  <!-- Header -->
  <header>
    <h1>Resultado Coproparasitológico</h1>
    <button class="btn-dashboard" onclick="window.location.href='{{ route('dashboard') }}'">⬅ Volver al Dashboard</button>
  </header>

  <main>
    <h2>Registrar Resultados - Técnica: {{ $tecnica->nombre }}</h2>

    <form action="{{ route('resultados.store_resultado_multiple', [$remisionRecibeId, $tecnica->id]) }}" method="POST">
      @csrf

      <div class="animal-grid">
        @foreach($animales as $animal)
          <fieldset class="animal-card">
            <legend>🐾 {{ $animal->nombre }} (ID: {{ $animal->id }})</legend>

            <input type="hidden" name="animal_id[{{ $animal->id }}]" value="{{ $animal->id }}">

            <div class="form-grid">
              <div class="form-group">
                <label>Código Interno</label>
                <input type="text" name="codigo_interno[{{ $animal->id }}]" value="{{ old('codigo_interno.'.$animal->id, $animal->codigo_interno ?? '') }}">
              </div>

              <div class="form-group">
                <label>Cantidad Muestra</label>
                <input type="text" name="cantidad_muestra[{{ $animal->id }}]" value="{{ old('cantidad_muestra.'.$animal->id) }}">
              </div>

              <div class="form-group">
                <label>Solución Flotación</label>
                <input type="text" name="solucion_flotacion[{{ $animal->id }}]" value="{{ old('solucion_flotacion.'.$animal->id) }}">
              </div>

              <div class="form-group">
                <label>Strongylida C1</label>
                <input type="text" name="strongylida_c1[{{ $animal->id }}]" value="{{ old('strongylida_c1.'.$animal->id) }}">
              </div>

              <div class="form-group">
                <label>Strongylida C2</label>
                <input type="text" name="strongylida_c2[{{ $animal->id }}]" value="{{ old('strongylida_c2.'.$animal->id) }}">
              </div>

              <div class="form-group">
                <label>Strongylus C1</label>
                <input type="text" name="strongylus_c1[{{ $animal->id }}]" value="{{ old('strongylus_c1.'.$animal->id) }}">
              </div>

              <div class="form-group">
                <label>Strongylus C2</label>
                <input type="text" name="strongylus_c2[{{ $animal->id }}]" value="{{ old('strongylus_c2.'.$animal->id) }}">
              </div>

              <div class="form-group">
                <label>Moniezia C1</label>
                <input type="text" name="moniezia_c1[{{ $animal->id }}]" value="{{ old('moniezia_c1.'.$animal->id) }}">
              </div>

              <div class="form-group">
                <label>Moniezia C2</label>
                <input type="text" name="moniezia_c2[{{ $animal->id }}]" value="{{ old('moniezia_c2.'.$animal->id) }}">
              </div>

              <div class="form-group">
                <label>Eimeria C1</label>
                <input type="text" name="eimeria_c1[{{ $animal->id }}]" value="{{ old('eimeria_c1.'.$animal->id) }}">
              </div>

              <div class="form-group">
                <label>Eimeria C2</label>
                <input type="text" name="eimeria_c2[{{ $animal->id }}]" value="{{ old('eimeria_c2.'.$animal->id) }}">
              </div>

              <div class="form-group" style="grid-column: span 2;">
                <label>Observaciones</label>
                <textarea name="observaciones[{{ $animal->id }}]">{{ old('observaciones.'.$animal->id) }}</textarea>
              </div>
            </div>
          </fieldset>
        @endforeach
      </div>

      <button type="submit" class="btn-submit">💾 Guardar Resultados</button>
    </form>
  </main>
</body>
</html>
