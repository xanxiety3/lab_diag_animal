<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Remisiones</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

    <input type="checkbox" id="menu-toggle" />
    <label for="menu-toggle" class="menu-icon">&#9776;</label>

    <!-- Sidebar izquierdo -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('img/logoSinfondo.png') }}" alt="Logo" class="logo">
            <h2>Laboratorio</h2>
        </div>
        <nav>
            <a href="{{ route('registro.wizard') }}">➕ Nueva Remisión</a>
            <a href="{{ route('resultados.vista') }}">📊 Resultados</a>
            <a href="{{ route('register') }}">👤 Registrar Usuario</a>
            <a href="{{ route('dashboard.monitoreo') }}">📊 Monitoreo de temperatura y humedad</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">🚪 Cerrar Sesión</button>
            </form>
        </nav>
    </aside>

    <main class="main-content">
        <header>
            <h1>📋 Dashboard de Remisiones Recibidas</h1>
        </header>

        <!-- Filtros -->
        <section class="filtros">
            <form action="{{ route('dashboard') }}" method="GET">
                <label for="filtro-resultado">Resultado:</label>
                <select name="filtro_resultado" id="filtro-resultado">
                    <option value="todos" {{ request('filtro_resultado') == 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="con" {{ request('filtro_resultado') == 'con' ? 'selected' : '' }}>Con resultado
                    </option>
                    <option value="sin" {{ request('filtro_resultado') == 'sin' ? 'selected' : '' }}>Sin resultado
                    </option>
                </select>

                <label for="filtro-estado">Estado:</label>
                <select name="filtro_estado" id="filtro-estado">
                    <option value="todos" {{ request('filtro_estado') == 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="aceptadas" {{ request('filtro_estado') == 'aceptadas' ? 'selected' : '' }}>Aceptadas
                    </option>
                    <option value="rechazadas" {{ request('filtro_estado') == 'rechazadas' ? 'selected' : '' }}>
                        Rechazadas</option>
                </select>

                <button type="submit">Aplicar</button>
            </form>
        </section>

        <!-- Tabla de remisiones -->
        <section class="tabla-remisiones">
            <table>
                <thead>
                    <tr>
                        <th>ID Remisión</th>
                        <th>Fecha</th>
                        <th>Responsable</th>
                        <th>Muestra/Técnica</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($remisiones as $remision)
                        <tr>
                            <td>{{ $remision->muestra_enviada_id }}</td>
                            <td>{{ $remision->fecha }}</td>
                            <td>{{ $remision->responsable->name ?? 'Sin responsable' }}</td>
                            <td>
                                @if ($remision->remision_muestra_envio && $remision->remision_muestra_envio->tiposMuestras)
                                    @foreach ($remision->remision_muestra_envio->tiposMuestras as $tipo)
                                        <span class="badge">{{ $tipo->nombre }}</span>
                                    @endforeach
                                @else
                                    Sin tipo
                                @endif
                            </td>
                            <td>
                                {{ $remision->registro_resultado ? '✅ Resultado registrado' : '⏳ Sin resultado' }}
                                {{ $remision->rechazada ? '❌ Rechazada' : '✔️ Aceptada' }}
                            </td>
                            <td>
                                <div class="acciones">
                                    <!-- Botón Ver -->
                                    <a href="{{ route('show.remision', $remision->muestra_enviada_id) }}"
                                        class="btn-ver">Ver</a>

                                    <!-- Botón Descargar Word -->
                                    <a href="{{ route('export.remision.word', $remision->muestra_enviada_id) }}"
                                        class="btn-descargar" title="Descargar Word">
                                        Descargar📄
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="paginacion">
                {{ $remisiones->links() }}
            </div>
        </section>
    </main>

</body>

</html>
