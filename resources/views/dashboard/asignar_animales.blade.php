<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar animales - Técnica {{ $tecnica->nombre }}</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #e8f0ea;
            color: #2d3436;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1000px;
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

        /* Tarjeta */
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

        /* Botones */
        .btn {
            display: inline-block;
            padding: 8px 14px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background: #2ecc71;
            transform: translateY(-2px);
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="btn-dashboard">⬅️ Volver al Dashboard</a>

        <h1>🐾 Asignar animales a la técnica "{{ $tecnica->nombre }}"</h1>
        <h2>📋 Remisión #{{ $remision->id }}</h2>

        <div class="card">
            @if ($animales->isNotEmpty())
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
                            @foreach($animales as $animal)
                                <tr>
                                    <td><input type="checkbox" name="animales[]" value="{{ $animal->id }}"></td>
                                    <td>{{ $animal->nombre }}</td>
                                    <td>{{ $animal->especie?->nombre ?? '-' }}</td>
                                    <td>{{ $animal->raza?->nombre ?? '-' }}</td>
                                    <td>{{ $animal->edad }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit" class="btn">Guardar asignación</button>
                </form>
            @else
                <p>No hay animales asociados a esta remisión.</p>
            @endif
        </div>
    </div>
</body>
</html>
