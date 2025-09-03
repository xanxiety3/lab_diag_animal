<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Resultados - Técnica</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f8f9fa; }
        h2 { color: #333; }
        .asignacion-list { margin-top: 20px; padding: 15px; background: white; border: 1px solid #ccc; border-radius: 8px; max-width: 600px; }
        .asignacion-item { margin: 12px 0; }
        label { display: block; margin-bottom: 4px; font-weight: bold; }
        input[type="text"], textarea { width: 100%; padding: 6px; border-radius: 4px; border: 1px solid #ccc; }
        .btn { margin-top: 20px; padding: 10px 15px; border: none; border-radius: 5px; background-color: #007bff; color: white; cursor: pointer; }
        .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>

    <h2>Registrar resultados para la técnica: {{ $tecnica->nombre }}</h2>
    <p>Remisión recibida #{{ $remisionRecibe->id }}</p>

    <form method="POST" action="{{ route('tecnicas.resultados.guardar', [$remisionRecibe->id, $tecnica->id]) }}">
        @csrf

        <div class="asignacion-list">
            @forelse($asignaciones as $asignacion)
                <div class="asignacion-item">
                    <label for="resultado_{{ $asignacion->asignacion_id }}">
                        Animal: {{ $asignacion->animal }}
                    </label>
                    <textarea name="resultados[{{ $asignacion->asignacion_id }}]" id="resultado_{{ $asignacion->asignacion_id }}" rows="2" placeholder="Ingrese el resultado..."></textarea>
                </div>
            @empty
                <p>No hay animales asignados a esta técnica todavía.</p>
            @endforelse
        </div>

        <button type="submit" class="btn">Guardar Resultados</button>
    </form>

</body>
</html>
