<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Resultado Bearman - Remisión #{{ $remisionRecibeId }}</title>
    <style>
        /* Tarjetas individuales para cada animal */
.bearman-card {
    border-left: 5px solid #3498db;
}

.bearman-card legend {
    font-weight: bold;
    color: #3498db;
}

.bearman-card input, .bearman-card select, .bearman-card textarea {
    background-color: #f9faff;
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
    <h2>Registrar Resultado Bearman - Remisión #{{ $remisionRecibeId }}</h2>

    <form action="{{ url('/resultados/guardar-multiple/' . $remisionRecibeId . '/' . $tecnica->id) }}" method="POST">
        @csrf
        <table>
            <thead>
                <tr>
                    <th>Animal</th>
                    <th>Código Interno</th>
                    <th>Código Solicitud</th>
                    <th>Fecha Análisis</th>
                    <th>Cantidad Muestra</th>
                    <th>Larvas</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($animales as $animal)
                    <tr>
                        <td>{{ $animal->nombre }} (ID: {{ $animal->id }})</td>
                        <td>
                            <input type="text" name="codigo_interno[{{ $animal->id }}]"
                                value="{{ old('codigo_interno.' . $animal->id) }}">
                        </td>
                        <td>
                            <input type="text" name="codigo_solicitud[{{ $animal->id }}]"
                                value="{{ old('codigo_solicitud.' . $animal->id) }}">
                        </td>
                        <td>
                            <input type="date" name="fecha_analisis[{{ $animal->id }}]"
                                value="{{ old('fecha_analisis.' . $animal->id, date('Y-m-d')) }}">
                        </td>
                        <td>
                            <input type="text" name="cantidad_muestra[{{ $animal->id }}]"
                                value="{{ old('cantidad_muestra.' . $animal->id) }}">
                        </td>
                        <td>
                            <select name="larvas[{{ $animal->id }}]">
                                <option value="ausencia"
                                    {{ old('larvas.' . $animal->id) == 'ausencia' ? 'selected' : '' }}>Ausencia
                                </option>
                                <option value="presencia"
                                    {{ old('larvas.' . $animal->id) == 'presencia' ? 'selected' : '' }}>Presencia
                                </option>
                            </select>
                        </td>
                        <td>
                            <textarea name="observaciones[{{ $animal->id }}]">{{ old('observaciones.' . $animal->id) }}</textarea>
                        </td>
                        <!-- Input hidden con el pivot correcto -->
                        @php
                            $pivot = $animal->tecnicasAsignadas->where('tecnica_id', $tecnica->id)->first();
                        @endphp
                        <input type="hidden" name="pivot_id[{{ $animal->id }}]" value="{{ $pivot->id ?? '' }}">
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn">Guardar Resultados</button>
    </form>
</body>

</html>
