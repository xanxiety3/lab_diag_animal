<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Resultados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 10px;
            color: #333;
        }
        p {
            color: #555;
            margin-bottom: 20px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }
        .btn {
            display: block;
            padding: 15px;
            text-align: center;
            background: #eaf2ff;
            border: 1px solid #cfdaf1;
            border-radius: 6px;
            text-decoration: none;
            color: #2a4d9b;
            font-weight: bold;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #dbe7ff;
            border-color: #b6c9f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrar Resultados - Remisión #{{ $remision->id }}</h2>
        <p>Selecciona la técnica a la que deseas registrar resultados:</p>

        <div class="grid">
            @forelse($remision->tecnicas as $tecnica)
                <a href="{{ route('resultados.asignar_animales', ['remision' => $remision->id, 'tecnica' => $tecnica->id]) }}" class="btn">
                    {{ $tecnica->nombre }}
                </a>
            @empty
                <p>No se seleccionaron técnicas para esta remisión.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
