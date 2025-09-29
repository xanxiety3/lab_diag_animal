<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar animales - Técnica {{ $tecnica->nombre }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
        .btn { background: #007BFF; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>

    <h1>🐾 Asignar animales a la técnica "{{ $tecnica->nombre }}"</h1>
    <h2>📋 Remisión #{{ $remision->id }}</h2>

   @if ($remision->animales->isNotEmpty())
    <form method="POST" action="{{ route('resultados.guardar_animales', [
        'tecnica'        => $tecnica->id,
        'remisionRecibe' => $remisionRecibe->id
    ]) }}">
        @csrf

        <input type="hidden" name="remision_id" value="{{ $remision->id }}">
        <input type="hidden" name="tecnica_id" value="{{ $tecnica->id }}">

        <table>
            <thead>
                <tr>
                    <th>Seleccionar</th>
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Edad</th>
                </tr>
            </thead>
            <tbody>
            @if ($animales->isNotEmpty())
    @foreach ($animales as $animal)
        <tr>
            <td>
                <input type="checkbox" name="animales[]" value="{{ $animal->id }}">
            </td>
            <td>{{ $animal->nombre }}</td>
            <td>{{ $animal->especie?->nombre ?? '—' }}</td>
            <td>{{ $animal->raza?->nombre ?? '—' }}</td>
            <td>{{ $animal->edad }}</td>
        </tr>
    @endforeach
@else
    <p>No hay animales asociados a esta remisión.</p>
@endif



</body>
</html>
