<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Resultado Copro Fresco</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9fdfb;
            margin: 20px;
            color: #333;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #27ae60; /* Verde corporativo */
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        header h1 {
            font-size: 22px;
            margin: 0;
        }

        header h2 {
            font-size: 15px;
            margin: 4px 0 0;
            font-weight: normal;
        }

        .back-btn {
            background: white;
            color: #27ae60;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #e9f7ef;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.08);
        }

        thead {
            background: #27ae60;
            color: white;
        }

        th, td {
            padding: 12px 10px;
            text-align: center;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        tbody tr:nth-child(even) {
            background: #f4faf6;
        }

        input[type="text"] {
            width: 100%;
            max-width: 120px;
            padding: 6px 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 13px;
            box-sizing: border-box;
        }

        input[readonly] {
            background: #f7f7f7;
            color: #555;
        }

        .btn {
            display: block;
            margin: 25px auto 0;
            padding: 12px 30px;
            background: #27ae60;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #1e874b;
        }

        @media (max-width: 1000px) {
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
        <div>
            <h1>📋 Registrar Resultado Copro Fresco</h1>
            <h2>Técnica "{{ $tecnica->nombre }}" - Remisión #{{ $remisionRecibeId }}</h2>
        </div>
        <a href="{{ route('dashboard') }}" class="back-btn">⬅ Volver al Dashboard</a>
    </header>

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
                        <th>Cél. Epiteliales</th>
                        <th>Cél. Vegetales</th>
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

            <button type="submit" class="btn">💾 Guardar Resultados</button>
        </form>
    @else
        <p>No hay animales asignados a esta técnica.</p>
    @endif

</body>
</html>
