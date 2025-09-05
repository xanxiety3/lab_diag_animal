<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Remisión</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2, h3 { margin-top: 20px; }
        .badge { display: inline-block; background: #007BFF; color: white; padding: 3px 8px; border-radius: 5px; margin: 2px; }
        .btn { display: inline-block; padding: 8px 12px; margin-top: 10px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #218838; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>

    <h1>📋 Detalle de Remisión #{{ $remision->id }}</h1>
<!-- RECEPCIÓN / RESPONSABLE -->
<div class="card">
    <h3>Recepción / Responsable</h3>
    <ul class="list">
        <li class="item">
            <b>Responsable (quien recibió):</b>
            {{ $remisionRecibe && $remisionRecibe->responsable ? $remisionRecibe->responsable->name : 'No registrado' }}
        </li>
        <li class="item">
            <b>Fecha recepción:</b>
            {{ $remisionRecibe?->fecha?->format('d/m/Y H:i') ?? '—' }}
        </li>
        <li class="item">
            <b>Registro de resultado:</b>
            {{ $remisionRecibe && $remisionRecibe->registro_resultado ? 'Sí' : 'No' }}
        </li>
        <li class="item">
            <b>Rechazada:</b>
            {{ $remisionRecibe && $remisionRecibe->rechazada ? 'Sí' : 'No' }}
        </li>
    </ul>
</div>

<!-- CLIENTE / PROPIETARIO -->
<div class="card">
    <h3>Cliente (propietario)</h3>
    <ul class="list">
        <li class="item">
            <b>Nombre:</b>
            {{ $remision->persona?->nombres ?? '' }} {{ $remision->persona?->apellidos ?? '' }}
        </li>
        <li class="item">
            <b>Documento:</b> {{ $remision->persona?->numero_documento ?? '—' }}
        </li>
        <li class="item">
            <b>Teléfono:</b> {{ $remision->persona?->telefono ?? '—' }}
        </li>
        <li class="item"><b>Direcciones:</b>
            @if($remision->persona && $remision->persona->direcciones->isNotEmpty())
                <ul style="margin-top:6px">
                    @foreach($remision->persona->direcciones as $direccion)
                        <li>📍 {{ $direccion->direccion_detallada }}</li>
                    @endforeach
                </ul>
            @else
                <span class="muted">Sin direcciones registradas.</span>
            @endif
        </li>
    </ul>
</div>

<!-- ANIMALES DEL CLIENTE -->
<div class="card">
    <h3>Animales</h3>

    @if ($remision->persona && $remision->persona->animales->isNotEmpty())
        <div class="animals">
            @foreach ($remision->persona->animales as $animal)
                <div class="animal">
                    <h4>🐾 {{ $animal->nombre ?? 'Sin nombre' }}</h4>
                    <div class="kv">
                        <span class="badge">Especie: {{ $animal->especie?->nombre ?? '—' }}</span>
                        <span class="badge">Raza: {{ $animal->raza?->nombre ?? '—' }}</span>
                        <span class="badge">Edad: {{ $animal->edad ?? '—' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty">No hay animales asociados al propietario.</div>
    @endif
</div>

    <h2>🧪 Muestras asociadas</h2>
    @if ($muestras->isNotEmpty())
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
                  @forelse ($remision->tiposMuestras as $tipo)
            <tr>
                <td>{{ $tipo->nombre }}</td>
                <td>{{ $tipo->pivot->cantidad_muestra ?? '-' }}</td>
                <td>{{ $tipo->pivot->refrigeracion ? 'Sí' : 'No' }}</td>
                <td>{{ $tipo->pivot->observaciones ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No hay muestras asociadas</td>
            </tr>
        @endforelse
            </tbody>
        </table>


        

    @endif

   <h2>🔬 Técnicas asociadas</h2>

@if ($remision->remision_muestra_recibe && $remision->remision_muestra_recibe->tecnicas->isNotEmpty())
    <ul>
        @foreach ($remision->remision_muestra_recibe->tecnicas as $tecnica)
            <li>{{ $tecnica->nombre }}</li>
        @endforeach
    </ul>
@else
    <p>No hay técnicas asociadas</p>
@endif


    <h2>⚡ Estado</h2>
    <p>
        {{ $remisionRecibe && $remisionRecibe->registro_resultado ? '✅ Resultado registrado' : '⏳ Sin resultado' }}
        {{ $remisionRecibe && $remisionRecibe->rechazada ? '❌ Rechazada' : '✔️ Aceptada' }}
    </p>

   @if ($remisionRecibe && !$remisionRecibe->rechazada && !$remisionRecibe->registro_resultado)
    <a class="btn" href="{{ route('resultados.elegir_tecnica', $remision->id) }}">
        ➕ Registrar resultados
    </a>
@endif


</body>
</html>
