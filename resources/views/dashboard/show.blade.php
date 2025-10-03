<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Remisión</title>
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
</head>
<body>

<header>
    <div class="logo-container">
        <img src="{{ asset('img/logoSinfondo.png') }}" alt="Logo">
        <h1>Detalle de Remisión</h1>
    </div>
    <a href="{{ route('dashboard') }}">
        <button class="btn-back">⬅ Volver al Dashboard</button>
    </a>
</header>

<main>
    <div class="container">
        <h1>📋 Remisión #{{ $remision->id }}</h1>

        <div class="card-grid">
            <!-- Recepción / Responsable -->
            <div class="card">
                <h3>Recepción / Responsable</h3>
                <ul class="list">
                    <li><b>Responsable:</b> {{ $remisionRecibe?->responsable?->name ?? 'No registrado' }}</li>
                    <li><b>Fecha recepción:</b> {{ $remisionRecibe?->fecha?->format('d/m/Y H:i') ?? '—' }}</li>
                    <li><b>Resultado registrado:</b> {{ $remisionRecibe && $remisionRecibe->registro_resultado ? 'Sí' : 'No' }}</li>
                    <li><b>Rechazada:</b> {{ $remisionRecibe && $remisionRecibe->rechazada ? 'Sí' : 'No' }}</li>
                </ul>
            </div>

            <!-- Cliente -->
            <div class="card">
                <h3>Cliente (propietario)</h3>
                <ul class="list">
                    <li><b>Nombre:</b> {{ $remision->persona?->nombres ?? '' }} {{ $remision->persona?->apellidos ?? '' }}</li>
                    <li><b>Documento:</b> {{ $remision->persona?->numero_documento ?? '—' }}</li>
                    <li><b>Teléfono:</b> {{ $remision->persona?->telefono ?? '—' }}</li>
                    <li><b>Direcciones:</b>
                        @if($remision->persona && $remision->persona->direcciones->isNotEmpty())
                            <ul class="sub-list">
                                @foreach($remision->persona->direcciones as $direccion)
                                    <li>📍 {{ $direccion->direccion_detallada }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="muted">Sin direcciones registradas</span>
                        @endif
                    </li>
                </ul>
            </div>

            <!-- Animales -->
            <div class="card">
                <h3>Animales</h3>
                <div class="animals">
                    @if ($remision->persona && $remision->persona->animales->isNotEmpty())
                        @foreach ($remision->persona->animales as $animal)
                        <div class="animal">
                            <h4>🐾 {{ $animal->nombre ?? 'Sin nombre' }}</h4>
                            <span class="badge">Especie: {{ $animal->especie?->nombre ?? '—' }}</span>
                            <span class="badge">Raza: {{ $animal->raza?->nombre ?? '—' }}</span>
                            <span class="badge">Edad: {{ $animal->edad ?? '—' }}</span>
                        </div>
                        @endforeach
                    @else
                        <p class="muted">No hay animales asociados</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Muestras -->
        <section>
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
                            <tr><td colspan="4">No hay muestras asociadas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </section>

        <!-- Técnicas -->
        <section>
            <h2>🔬 Técnicas asociadas</h2>
            @if ($remision->remision_muestra_recibe && $remision->remision_muestra_recibe->tecnicas->isNotEmpty())
                <ul class="list">
                    @foreach ($remision->remision_muestra_recibe->tecnicas as $tecnica)
                        <li>{{ $tecnica->nombre }}</li>
                    @endforeach
                </ul>
            @else
                <p class="muted">No hay técnicas asociadas</p>
            @endif
        </section>

        <!-- Estado -->
        <section>
            <h2>⚡ Estado</h2>
            <p>
                {{ $remisionRecibe && $remisionRecibe->registro_resultado ? '✅ Resultado registrado' : '⏳ Sin resultado' }}
                {{ $remisionRecibe && $remisionRecibe->rechazada ? '❌ Rechazada' : '✔️ Aceptada' }}
            </p>

            @if ($remisionRecibe && !$remisionRecibe->rechazada && !$remisionRecibe->registro_resultado)
                <a class="btn-action" href="{{ route('resultados.elegir_tecnica', $remision->id) }}">
                    ➕ Registrar resultados
                </a>
            @endif
        </section>
    </div>
</main>

</body>
</html>
