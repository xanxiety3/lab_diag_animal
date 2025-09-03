<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar animales a técnica</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f8f9fa; }
        h2, h3 { color: #333; }
        .animal-list { margin-top: 20px; padding: 15px; background: white; border: 1px solid #ccc; border-radius: 8px; width: 400px; }
        .animal-item { margin: 8px 0; }
        .btn { margin-top: 20px; padding: 10px 15px; border: none; border-radius: 5px; background-color: #007bff; color: white; cursor: pointer; }
        .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>

    <h2>Asignar animales a la técnica: {{ $tecnica->nombre }}</h2>
    <p>Remisión recibida #{{ $remisionRecibe->id }}</p>

    <form method="POST" action="{{ route('tecnicas.resultados.index', [$remisionRecibe->id, $tecnica->id]) }}">
        @csrf

        <h3>Animales del dueño: {{ $persona->nombres ?? '' }} {{ $persona->apellidos ?? '' }}</h3>

        <div class="animal-list">
            @forelse($animales as $animal)
                <div class="animal-item">
                    <label>
                        <input type="checkbox" name="animales[]" value="{{ $animal->id }}">
                        {{ $animal->nombre ?? 'Sin nombre' }}
                        ({{ $animal->especie->nombre ?? 'Especie desconocida' }})
                    </label>
                </div>
            @empty
                <p>No hay animales registrados para este dueño.</p>
            @endforelse
        </div>

        <button type="submit" class="btn">Guardar selección</button>
    </form>

</body>
</html>
