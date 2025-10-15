<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Resultado Bearman - Remisión #{{ $remisionRecibeId }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4fdf8;
            margin: 20px;
            color: #ffffff;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
  background: #27ae60;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
  background: #27ae60;
            color: #fff;
        }

        th, td {
            padding: 12px 10px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        tbody tr:nth-child(even) {
            background: #f9fefb;
        }

        input[type="text"], 
        input[type="date"], 
        select, 
        textarea {
            width: 95%;
            padding: 6px 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 13px;
        }

        textarea {
            resize: vertical;
            height: 50px;
        }

        .btn {
            display: block;
            margin: 25px auto;
            padding: 12px 30px;
  background: #27ae60;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #276749; /* Verde más oscuro */
        }

        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
      <header>

    <button class="btn-dashboard" onclick="window.location.href='{{ route('dashboard') }}'">⬅ Volver al Dashboard</button>
  </header>

    <h2>Registrar Resultado Bearman - Remisión #{{ $remisionRecibeId }}</h2>

    <form action="{{ url('/resultados/guardar-multiple/' . $remisionRecibeId . '/' . $tecnica->id) }}" method="POST">
        @csrf
        <table>
            <thead>
                <tr>
                    <th>Animal</th>
                    <th>Cód. Interno</th>
                    <th>Cód. Solicitud</th>
                    <th>Fecha Análisis</th>
                    <th>Cant. Muestra</th>
                    <th>Larvas</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($animales as $animal)
                    <tr>
                        <td><strong>{{ $animal->nombre }}</strong><br>(ID: {{ $animal->id }})</td>
                        <td><input type="text" name="codigo_interno[{{ $animal->id }}]"
                            value="{{ old('codigo_interno.' . $animal->id) }}"></td>
                        <td><input type="text" name="codigo_solicitud[{{ $animal->id }}]"
                            value="{{ old('codigo_solicitud.' . $animal->id) }}"></td>
                        <td><input type="date" name="fecha_analisis[{{ $animal->id }}]"
                            value="{{ old('fecha_analisis.' . $animal->id, date('Y-m-d')) }}"></td>
                        <td><input type="text" name="cantidad_muestra[{{ $animal->id }}]"
                            value="{{ old('cantidad_muestra.' . $animal->id) }}"></td>
                        <td>
                            <select name="larvas[{{ $animal->id }}]">
                                <option value="ausencia" {{ old('larvas.' . $animal->id) == 'ausencia' ? 'selected' : '' }}>Ausencia</option>
                                <option value="presencia" {{ old('larvas.' . $animal->id) == 'presencia' ? 'selected' : '' }}>Presencia</option>
                            </select>
                        </td>
                        <td><textarea name="observaciones[{{ $animal->id }}]">{{ old('observaciones.' . $animal->id) }}</textarea></td>
                        @php
                            $pivot = $animal->tecnicasAsignadas->where('tecnica_id', $tecnica->id)->first();
                        @endphp
                        <input type="hidden" name="pivot_id[{{ $animal->id }}]" value="{{ $pivot->id ?? '' }}">
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn">💾 Guardar Resultados</button>
    </form>
</body>
</html>
