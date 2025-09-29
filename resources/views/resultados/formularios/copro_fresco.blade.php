<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Resultado Copro Fresco</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #f4f4f4; }
        input[type="text"], input[type="number"] { width: 100%; box-sizing: border-box; }
        .btn { background: #007BFF; color: white; padding: 8px 12px; border-radius: 4px; text-decoration: none; cursor: pointer; border: none; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>

    <h1>📋 Registrar Resultado Copro Fresco - Técnica "{{ $tecnica->nombre }}"</h1>
    <h2>Remisión #{{ $remisionRecibeId }}</h2>

    @if($animales->isNotEmpty())
        <form method="POST" action="{{ route('resultados.store_resultado_multiple', [$remisionRecibeId, $tecnica->id]) }}">
            @csrf

            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Sexo</th>
                        <th>Especie</th>
                        <th>Color</th>
                        <th>Consistencia</th>
                        <th>Moco</th>
                        <th>Sangre</th>
                        <th>Células Epiteliales</th>
                        <th>Células Vegetales</th>
                        <th>Huevos</th>
                        <th>Quistes</th>
                        <th>Levaduras</th>
                        <th>Otros</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($animales as $animal)
                        <tr>
                            <td>
                                <input type="text" name="codigo_interno[{{ $animal->id }}]" value="{{ $animal->id }}" readonly>
                                <!-- 👇 Aquí ya usamos el pivot_id de la relación -->
                                <input type="hidden" name="pivot_id[{{ $animal->id }}]" value="{{ $animal->tecnicasAsignadas->first()->pivot->id }}">
                            </td>
                            <td><input type="text" value="{{ $animal->nombre ?? '—' }}" readonly></td>
                            <td>
                                <input type="text" value="{{ $animal->sexo?->descripcion ?? '—' }}" readonly>
                                <input type="hidden" name="sexo[{{ $animal->id }}]" value="{{ $animal->sexo?->descripcion ?? '' }}">
                            </td>

                            <td>
                                <input type="text" value="{{ $animal->especie?->nombre ?? '—' }}" readonly>
                                <input type="hidden" name="especie[{{ $animal->id }}]" value="{{ $animal->especie?->nombre ?? '' }}">
                            </td>

                            <td><input type="text" name="color[{{ $animal->id }}]"></td>
                            <td><input type="text" name="consistencia[{{ $animal->id }}]"></td>
                            <td><input type="text" name="moco[{{ $animal->id }}]"></td>
                            <td><input type="text" name="sangre[{{ $animal->id }}]"></td>
                            <td><input type="text" name="celulas_epiteliales[{{ $animal->id }}]"></td>
                            <td><input type="text" name="celulas_vegetales[{{ $animal->id }}]"></td>
                            <td><input type="text" name="huevos[{{ $animal->id }}]"></td>
                            <td><input type="text" name="quistes[{{ $animal->id }}]"></td>
                            <td><input type="text" name="levaduras[{{ $animal->id }}]"></td>
                            <td><input type="text" name="otros[{{ $animal->id }}]"></td>
                            <td><input type="text" name="observaciones[{{ $animal->id }}]"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br>
            <button type="submit" class="btn">💾 Guardar Resultados</button>
        </form>
    @else
        <p>No hay animales asignados a esta técnica.</p>
    @endif

</body>
</html>
