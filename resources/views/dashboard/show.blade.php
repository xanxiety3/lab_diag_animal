<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Remisión #{{ $remision->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        :root {
            --bg: #f5f7fb;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --primary: #3b82f6;
            --primary-600: #2563eb;
            --ok: #16a34a;
            --warn: #f59e0b;
            --bad: #ef4444;
            --chip: #eef2ff;
            --chip-text: #3730a3;
            --border: #e5e7eb;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Noto Sans", "Helvetica Neue", Arial;
            background: var(--bg);
            color: var(--text);
        }

        .wrap {
            max-width: 1100px;
            margin: 24px auto;
            padding: 0 16px;
        }

        .header {
            display: flex;
            gap: 16px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .title {
            font-size: clamp(20px, 3vw, 28px);
            font-weight: 700;
            margin: 0;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px
        }

        .chip {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            background: var(--chip);
            color: var(--chip-text);
            border: 1px solid #e0e7ff;
        }

        .chip.ok {
            background: #ecfdf5;
            color: #065f46;
            border-color: #d1fae5
        }

        .chip.bad {
            background: #fef2f2;
            color: #991b1b;
            border-color: #fee2e2
        }

        .chip.warn {
            background: #fffbeb;
            color: #92400e;
            border-color: #fde68a
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width:900px) {
            .grid {
                grid-template-columns: 1.2fr 1fr;
            }
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #374151
        }

        .list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 8px
        }

        .item b {
            color: #374151
        }

        .muted {
            color: var(--muted)
        }

        .kv {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #0f172a;
            font-size: 12px;
            border: 1px solid #e2e8f0
        }

        .animals {
            display: grid;
            gap: 10px
        }

        .animal {
            border: 1px dashed var(--border);
            border-radius: 12px;
            padding: 10px
        }

        .animal h4 {
            margin: .2rem 0 .4rem;
            font-size: 14px
        }

        .muestras ul {
            margin: 0;
            padding-left: 18px
        }

        .muestras li {
            margin: 6px 0
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 14px
        }

        .btn {
            appearance: none;
            border: none;
            background: var(--primary);
            color: #fff;
            padding: 10px 14px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600
        }

        .btn:hover {
            background: var(--primary-600)
        }

        .btn.ghost {
            background: #fff;
            color: var(--primary);
            border: 1px solid var(--primary)
        }

        .empty {
            padding: 12px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px dashed var(--border);
            color: var(--muted)
        }

        .sep {
            height: 1px;
            background: var(--border);
            margin: 8px 0 12px
        }
    </style>
</head>

<body>
    <div class="wrap">

        <div class="header">
            <h1 class="title">Remisión #{{ $remision->id }}</h1>
            <div class="chips">
                <span class="chip">{{ $remision->fecha?->format('d/m/Y H:i') ?? '—' }}</span>
                @if ($remision->rechazada)
                    <span class="chip bad">Rechazada</span>
                @else
                    <span class="chip ok">Aceptada</span>
                @endif
                @if ($remision->registro_resultado)
                    <span class="chip ok">Con resultado</span>
                @else
                    <span class="chip warn">Sin resultado</span>
                @endif
            </div>
        </div>

        <div class="grid">
            <!-- Columna izquierda -->
            <div class="col">
                <div class="card">
                    <h3>Datos generales</h3>
                    <ul class="list">
                        <li class="item"><b>Fecha:</b> {{ $remision->fecha?->format('d/m/Y H:i') ?? '—' }}</li>
                        <li class="item"><b>Estado:</b> {{ $remision->rechazada ? 'Rechazada' : 'Aceptada' }}</li>
                        <li class="item"><b>Observaciones:</b> <span
                                class="muted">{{ $remision->observaciones ?: 'Ninguna' }}</span></li>
                    </ul>
                </div>

                <div class="card">
                    <h3>Propietario</h3>
                    <ul class="list">
                        <li class="item"><b>Nombre:</b> {{ $remision->persona?->nombres }}
                            {{ $remision->persona?->apellidos }}</li>
                        <li class="item"><b>Documento:</b> {{ $remision->persona?->numero_documento ?: '—' }}</li>
                        <li class="item"><b>Teléfono:</b> {{ $remision->persona?->telefono ?: '—' }}</li>
                    </ul>
                    <div class="sep"></div>
                    <div>
                        <b>Direcciones:</b>
                        @if ($remision->persona?->direcciones && $remision->persona->direcciones->isNotEmpty())
                            <ul class="list" style="margin-top:8px">
                                @foreach ($remision->persona->direcciones as $direccion)
                                    <li class="item">📍 {{ $direccion->direccion_detallada }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="empty">Sin direcciones registradas.</div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <h3>Animales</h3>
                    @if ($remision->persona?->animales && $remision->persona->animales->isNotEmpty())
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
            </div>

            <!-- Columna derecha -->
            <div class="col">
                <div class="card muestras">
                    <!-- Técnicas asociadas -->
<h3>Técnicas asociadas</h3>
@if ($tecnicas && $tecnicas->isNotEmpty())
    <div style="display:flex;flex-wrap:wrap;gap:8px;">
        @foreach ($tecnicas as $t)
            <span class="badge"
                style="padding:6px 10px;border-radius:999px;background:#eef2ff;border:1px solid #dbeafe;">
                {{ $t->nombre }}
                @if (isset($t->veces))
                    <small style="opacity:.7">({{ $t->veces }})</small>
                @endif
            </span>
        @endforeach
    </div>
@else
    <p class="muted">No hay técnicas asociadas a esta remisión (aún).</p>
@endif

<hr>

<!-- Muestras asociadas -->
<h3>Muestras asociadas</h3>
@if ($muestras && $muestras->isNotEmpty())
    <div style="display:flex;flex-wrap:wrap;gap:8px;">
        @foreach ($muestras as $m)
            <span class="badge"
                style="padding:6px 10px;border-radius:999px;background:#ecfdf5;border:1px solid #d1fae5;">
                {{ $m->nombre }}
                @if (isset($m->pivot->cantidad_muestra))
                    <small style="opacity:.7">({{ $m->pivot->cantidad_muestra }} muestras)</small>
                @endif
                @if (isset($m->pivot->refrigeracion))
                    <small style="opacity:.7">{{ $m->pivot->refrigeracion ? 'Refrigerada' : 'Sin refrigerar' }}</small>
                @endif
            </span>
        @endforeach
    </div>
@else
    <p class="muted">No hay muestras asociadas a esta remisión (aún).</p>
@endif


                <div class="card">
                    <h3>Acciones</h3>
                    <div class="actions">
                        @if (
                            !$remision->rechazada &&
                                isset($remision->remision_muestra_recibe) &&
                                $remision->remision_muestra_recibe->isNotEmpty() &&
                                !$remision->registro_resultado)
                            <a class="btn"
                                href="{{ route('resultados.create', $remision->remision_muestra_recibe->first()->id) }}">➕
                                Registrar resultados</a>
                        @endif

                        <a class="btn ghost" href="{{ route('dashboard') }}">⬅ Volver</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
