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
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #dbe7ff;
            border-color: #b6c9f0;
        }
        .hidden { display: none; }
        .tecnicas-container { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrar Resultados - Remisión #{{ $remision->id }}</h2>

        {{-- Paso 1: Elegir muestra --}}
        <p>Selecciona la muestra:</p>
        <div class="grid">
            @foreach($remision->remision_muestra_envio->tiposMuestras as $muestra)
                <div class="btn"
                     onclick="mostrarTecnicas({{ $muestra->id }})">
                    {{ $muestra->nombre }} 
                    (Cantidad: {{ $muestra->pivot->cantidad_muestra }})
                </div>
            @endforeach
        </div>

        {{-- Paso 2: Mostrar técnicas dinámicamente --}}
        @foreach($remision->remision_muestra_envio->tiposMuestras as $muestra)
            <div id="tecnicas-{{ $muestra->id }}" class="tecnicas-container hidden">
                <h3>🔬 Técnicas para {{ $muestra->nombre }}</h3>
                <div class="grid">
                    @php
                        $tecnicas = DB::table('muestra_recibe_tecnica')
                            ->join('tecnicas_muestra', 'muestra_recibe_tecnica.tecnica_id', '=', 'tecnicas_muestra.id')
                            ->join('remision_muestra_recibe', 'muestra_recibe_tecnica.muestra_recibe_id', '=', 'remision_muestra_recibe.id')
                            ->where('remision_muestra_recibe.muestra_enviada_id', $remision->id)     
                            ->where('remision_muestra_recibe.muestra_enviada_id', $muestra->id) 
                            ->select('tecnicas_muestra.id', 'tecnicas_muestra.nombre')
                            ->distinct()
                            ->get();
                    @endphp

                    @forelse($tecnicas as $tecnica)
                        <a href="{{ route('resultados.asignar_animales', ['remision' => $remision->id, 'tecnica' => $tecnica->id]) }}" class="btn">
                            {{ $tecnica->nombre }}
                        </a>
                    @empty
                        <p>No hay técnicas asociadas a esta muestra.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function mostrarTecnicas(muestraId) {
            // Ocultar todas
            document.querySelectorAll('.tecnicas-container').forEach(el => el.classList.add('hidden'));
            // Mostrar solo la seleccionada
            document.getElementById('tecnicas-' + muestraId).classList.remove('hidden');
        }

        // Si solo hay una muestra, mostrar automáticamente sus técnicas
        document.addEventListener("DOMContentLoaded", function() {
            const muestras = document.querySelectorAll(".grid .btn");
            if (muestras.length === 1) {
                muestras[0].click();
            }
        });
    </script>
</body>
</html>
