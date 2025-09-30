<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados - Remisión #{{ $remision->id }}</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #e8f0ea;
            color: #2d3436;
            padding: 30px;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1, h2 {
            margin-bottom: 20px;
        }

        /* Botón volver */
        .btn-dashboard {
            display: inline-block;
            padding: 10px 20px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            margin-bottom: 25px;
            transition: all 0.3s;
        }
        .btn-dashboard:hover {
            background: #2ecc71;
            transform: translateY(-2px);
        }

        /* Tarjetas */
        .card {
            background: #ffffffcc;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            background: #ffffffcc;
            margin-bottom: 25px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: #27ae60;
            color: white;
        }

        tbody tr:hover {
            background: #f0f9f5;
        }

        /* Botones y badges */
        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin-left: 10px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn:hover {
            background: #2ecc71;
            transform: translateY(-2px);
        }

        .chip.ok {
            background: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 12px;
            margin-left: 8px;
            font-size: 13px;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        ul li {
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="btn-dashboard">⬅️ Volver al Dashboard</a>

        <h1>📋 Resultados - Remisión enviada #{{ $remision->id }}</h1>

        <!-- Muestras -->
        <div class="card">
            <h3>🧪 Muestras asociadas</h3>
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
        </div>

        <!-- Técnicas -->
        <div class="card">
            <h3>⚗️ Técnicas asociadas a la Remisión #{{ $remision->id }}</h3>
            @if($tecnicas->isNotEmpty())
                <ul>
                    @foreach($tecnicas as $tecnica)
                        <li>
                            {{ $tecnica->nombre }}
                            @if (!($tecnica->tiene_resultado ?? false))
                                <a href="{{ route('resultados.asignar_animales', [
                                    'remision' => $remision->id,
                                    'tecnica'  => $tecnica->id
                                ]) }}" class="btn">➕ Registrar resultado</a>
                            @else
                                <span class="chip ok">✅ Resultado registrado</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No hay técnicas asociadas a esta recepción.</p>
            @endif
        </div>
    </div>
</body>
</html>
