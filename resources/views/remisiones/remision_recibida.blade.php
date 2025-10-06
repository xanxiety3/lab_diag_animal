<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recepción de Muestra</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            background: #f5f7fa;
            color: #333;
        }

        header {
            background: #27ae60;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        header .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        header img { height: 40px; }
        header h1 { font-size: 1.2rem; margin: 0; }

        header .btn-back {
            background: white;
            color: #27ae60;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        header .btn-back:hover { background: #ecfdf3; }

        main { max-width: 900px; margin: 40px auto; padding: 20px; }
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        h2, h3 { margin-top: 0; color: #27ae60; }

        /* Checkbox con cantidad */
        .checkbox-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 12px;
            margin: 15px 0;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f9f9f9;
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            gap: 8px;
        }

        .checkbox-item:hover {
            background: #ecfdf3;
            border-color: #27ae60;
        }

        .checkbox-item input[type="checkbox"] { accent-color: #27ae60; }
        .checkbox-item input[type="number"] {
            width: 70px;
            padding: 4px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn {
            background: #27ae60;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: background 0.3s ease;
            margin-top: 20px;
        }
        .btn:hover { background: #219150; }

        .empty {
            background: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ffeeba;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo-container">
            <img src="{{ asset('img/logoSinfondo.png') }}" alt="Logo">
            <h1>Recepción de Muestra</h1>
        </div>
        <a href="{{ route('dashboard') }}">
            <button class="btn-back">⬅ Volver al Dashboard</button>
        </a>
    </header>

    <main>
        <div class="card">
            <h2>📋 Remisión #{{ $remision->id }}</h2>

            <form method="POST" action="{{ route('remisiones.recibida') }}">
                @csrf
                <input type="hidden" name="muestra_enviada_id" value="{{ $remision->id }}">

                <!-- Selección de Técnicas -->
                <h3>🧪 Seleccionar Técnicas</h3>
                <div class="checkbox-list">
                    @foreach ($tecnicas as $tecnica)
                        <div class="checkbox-item">
                            <label>
                                <input type="checkbox" name="tecnicas[{{ $tecnica->id }}][id]" value="{{ $tecnica->id }}">
                                {{ $tecnica->nombre }}
                            </label>
                            <input type="number" name="tecnicas[{{ $tecnica->id }}][cantidad]" placeholder="Cantidad" min="0">
                        </div>
                    @endforeach
                </div>

                <!-- Selección de Animales -->
                <h3>🐾 Animales Remitidos</h3>
                @if ($remision->persona && $remision->persona->animales->isNotEmpty())
                    <div class="checkbox-list">
                        @foreach ($remision->persona->animales as $animal)
                            <label class="checkbox-item">
                                <span>
                                    <input type="checkbox" name="animales[]" value="{{ $animal->id }}">
                                    {{ $animal->nombre }} ({{ $animal->especie->nombre ?? '—' }}, {{ $animal->edad }} años)
                                </span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="empty">⚠️ Este cliente no tiene animales registrados.</p>
                @endif

                <button type="submit" class="btn">💾 Guardar Recepción</button>
            </form>
        </div>
    </main>

</body>
</html>
