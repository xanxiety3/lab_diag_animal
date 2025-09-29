<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recepción de Muestra</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
        .btn { background: #28a745; color: white; padding: 8px 12px; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background: #218838; }
        .checkbox-list { margin: 10px 0; }
        .checkbox-list label { display: block; margin: 5px 0; }
    </style>
</head>
<body>

    <h1>📋 Recepción de muestra</h1>

    <h2>Remisión #{{ $remision->id }}</h2>

    <form method="POST" action="{{ route('remisiones.recibida') }}">
        @csrf

        <!-- ID de la remisión enviada -->
        <input type="hidden" name="muestra_enviada_id" value="{{ $remision->id }}">

        <!-- Selección de Técnicas -->
        <h3>🧪 Seleccionar técnicas</h3>
        <div class="checkbox-list">
            @foreach ($tecnicas as $tecnica)
                <label>
                    <input type="checkbox" name="tecnicas[]" value="{{ $tecnica->id }}">
                    {{ $tecnica->nombre }}
                </label>
            @endforeach
        </div>

        <!-- Selección de Animales -->
        <h3>🐾 Animales remitidos</h3>
        @if ($remision->persona && $remision->persona->animales->isNotEmpty())
            <div class="checkbox-list">
                @foreach ($remision->persona->animales as $animal)
                    <label>
                        <input type="checkbox" name="animales[]" value="{{ $animal->id }}">
                        {{ $animal->nombre }} ({{ $animal->especie->nombre ?? '—' }}, {{ $animal->edad }} años)
                    </label>
                @endforeach
            </div>
        @else
            <p>⚠️ Este cliente no tiene animales registrados.</p>
        @endif

        <br>
        <button type="submit" class="btn">💾 Guardar Recepción</button>
    </form>

</body>
</html>
