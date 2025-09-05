<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados - Remisión #{{ $remision->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
        .btn { background: #28a745; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; }
        .btn:hover { background: #218838; }
    </style>
</head>
<body>

    <h1>📋 Resultados - Remisión enviada #{{ $remision->id }}</h1>

    <h2>🧪 Muestras asociadas</h2>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Refrigeración</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($muestras as $muestra)
                <tr>
                    <td>{{ $muestra->nombre }}</td>
                    <td>{{ $muestra->pivot->cantidad_muestra }}</td>
                    <td>{{ $muestra->pivot->refrigeracion ? 'Sí' : 'No' }}</td>
                    <td>{{ $muestra->pivot->observaciones ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>🔬 Técnicas asociadas</h2>
    @if ($tecnicas->isNotEmpty())
        <ul>
            @foreach ($tecnicas as $tecnica)
                <li>
                    {{ $tecnica->nombre }}
                    <a href="{{ route('resultados.asignar_animales', [
                        'remision' => $remision->id,
                        'tecnica' => $tecnica->id
                    ]) }}" class="btn">
                        ➕ Registrar resultados
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p>No hay técnicas asociadas (aún no se recibió esta remisión).</p>
    @endif

</body>
</html>
