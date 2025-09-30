<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Resultado Copro Fresco</title>
    <style>
        .copro-card {
    border-left: 5px solid #f39c12;
}

.copro-card legend {
    font-weight: bold;
    color: #f39c12;
}

.copro-card input, .copro-card select, .copro-card textarea {
    background-color: #fffaf0;
}
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 20px;
    color: #333;
}

h1, h2 {
    color: #2c3e50;
    margin-bottom: 20px;
}

.btn {
    background-color: #28a745;
    color: white;
    padding: 10px 18px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-weight: bold;
    transition: 0.2s;
}

.btn:hover {
    background-color: #218838;
}

.dashboard-btn {
    background-color: #007BFF;
    margin-bottom: 20px;
}

.dashboard-btn:hover {
    background-color: #0056b3;
}

form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Tarjetas uniformes */
.card {
    background-color: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

/* Inputs y selects */
input[type="text"], input[type="number"], input[type="date"], select, textarea {
    width: 100%;
    padding: 8px;
    margin-top: 6px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
}

/* Textareas */
textarea {
    resize: vertical;
    min-height: 60px;
}

/* Tablas para Copro Fresco y Bearman */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background-color: #f0f0f0;
}

thead tr {
    background-color: #e9ecef;
}

tr:nth-child(even) {
    background-color: #fafafa;
}

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
