<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="{{ asset('css/remision_envio.css') }}">
    <title>Registro de Remisión</title>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="logo-container">
            <img src="{{ asset('img/logoSinfondo.png') }}" alt="Logo">
            <h1>Laboratorio Clínico Veterinario</h1>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-back-dashboard">🏠 Volver al Dashboard</a>

    </header>

    <main class="main-content">
        <form method="POST" action="{{ route('remisiones.store') }}" class="remision-form">
            @csrf

            <!-- DATOS GENERALES -->
            <section class="form-grid">
                <div class="form-card">
                    <h3>📅 Datos Generales</h3>
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" name="fecha" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="cliente_id">Cliente</label>
                        <input type="hidden" name="cliente_id" value="{{ $selectedCliente }}">
                        <input type="text" value="{{ $clientes->find($selectedCliente)?->nombres }}" readonly>
                    </div>
                </div>

                <div class="form-card">
                    <h3>📝 Observaciones</h3>
                    <div class="form-group">
                        <textarea id="observaciones" name="observaciones" placeholder="Escriba observaciones generales..."></textarea>
                    </div>
                </div>
            </section>

            <!-- TIPOS DE MUESTRA -->
            <section class="samples-section">
                <h3>🧪 Tipos de muestra</h3>
                <div class="samples-grid">
                    @foreach ($tiposMuestra as $tipo)
                        <div class="sample-box">
                            <div class="form-group checkbox">
                                <input type="checkbox" id="tipo_{{ $tipo->id }}"
                                    name="tipos_muestra[{{ $tipo->id }}][activo]" value="1">
                                <label for="tipo_{{ $tipo->id }}"><strong>{{ $tipo->nombre }}</strong></label>
                            </div>

                            <div class="form-group">
                                <label for="cantidad_{{ $tipo->id }}">Cantidad</label>
                                <input type="number" name="tipos_muestra[{{ $tipo->id }}][cantidad]"
                                    id="cantidad_{{ $tipo->id }}" min="1"
                                    max="{{ $clientes->find($selectedCliente)?->animales->count() ?? 1 }}"
                                    title="Máximo {{ $clientes->find($selectedCliente)?->animales->count() ?? 1 }} muestras permitidas">

                            </div>

                            <div class="form-group">
                                <label for="refrigeracion_{{ $tipo->id }}">Refrigeración</label>
                                <select name="tipos_muestra[{{ $tipo->id }}][refrigeracion]"
                                    id="refrigeracion_{{ $tipo->id }}">
                                    <option value="">--</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="obs_{{ $tipo->id }}">Observaciones</label>
                                <textarea name="tipos_muestra[{{ $tipo->id }}][observaciones]" id="obs_{{ $tipo->id }}"></textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- BOTÓN -->
            <div class="form-actions">
                <button type="submit" class="submit-btn">💾 Guardar remisión</button>
            </div>
        </form>
    </main>
    <script>
        < script >
            document.querySelector('.remision-form').addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('input[type="checkbox"][name*="[activo]"]:checked');
                if (checked.length === 0) {
                    e.preventDefault();
                    alert("⚠️ Debe seleccionar al menos un tipo de muestra.");
                    return;
                }

                checked.forEach(box => {
                    const tipoId = box.id.split('_')[1];
                    const cantidad = document.querySelector(`#cantidad_${tipoId}`).value;
                    if (!cantidad || cantidad < 1) {
                        e.preventDefault();
                        alert(`⚠️ Ingrese una cantidad válida para la muestra seleccionada (ID ${tipoId}).`);
                    }
                });
            });
    </script>
    </script>
</body>

</html>
