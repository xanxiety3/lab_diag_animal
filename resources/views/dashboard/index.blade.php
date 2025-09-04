<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard de Remisiones Recibidas</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

    <header>
        <h1>📋 Dashboard de Remisiones Recibidas</h1>
        <nav>
            <a href="{{ route('registro.wizard') }}">➕ Nueva Remisión</a>
            <a href="{{ route('logout') }}">🚪 Cerrar Sesión</a>
        </nav>
    </header>

    <main>
        <section class="tabla-remisiones">

            <!-- FILTROS -->
            <div class="filtros">
                <form action="{{ route('dashboard') }}" method="GET">
                    <label for="filtro-resultado">Resultado:</label>
                    <select name="filtro_resultado" id="filtro-resultado">
                        <option value="todos" {{ request('filtro_resultado') == 'todos' ? 'selected' : '' }}>Todos</option>
                        <option value="con" {{ request('filtro_resultado') == 'con' ? 'selected' : '' }}>Con resultado</option>
                        <option value="sin" {{ request('filtro_resultado') == 'sin' ? 'selected' : '' }}>Sin resultado</option>
                    </select>

                    <label for="filtro-estado">Estado:</label>
                    <select name="filtro_estado" id="filtro-estado">
                        <option value="todos" {{ request('filtro_estado') == 'todos' ? 'selected' : '' }}>Todos</option>
                        <option value="aceptadas" {{ request('filtro_estado') == 'aceptadas' ? 'selected' : '' }}>Aceptadas</option>
                        <option value="rechazadas" {{ request('filtro_estado') == 'rechazadas' ? 'selected' : '' }}>Rechazadas</option>
                    </select>

                    <button type="submit">Aplicar</button>
                </form>
            </div>
            <!-- FIN FILTROS -->

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
                            <!-- Mostramos el id real de la remisión enviada -->
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
                                <!-- Enlace con muestra_enviada_id -->
                                <a href="{{ route('show.remision', $remision->muestra_enviada_id) }}" class="btn-ver">Ver</a>
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
