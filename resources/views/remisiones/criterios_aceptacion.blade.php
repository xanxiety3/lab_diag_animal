<style>
    /* 🎨 PALETA PRINCIPAL */
    :root {
        --verde: #39A900;
        --verde-oscuro: #2e8d00;
        --azul: #40647A;
        --gris-fondo: #f3f6f5;
        --gris-borde: #e2e8e7;
        --texto: #333;
    }

    body {
        background: var(--gris-fondo);
        color: var(--texto);
        font-family: 'Poppins', sans-serif;
    }

    /* 📦 CONTENEDOR PRINCIPAL */
    .remision-container {
        background: #fff;
        border-radius: 16px;
        padding: 30px 40px;
        box-shadow: 0 4px 16px rgba(64, 100, 122, 0.15);
        border: 1px solid var(--gris-borde);
        margin-bottom: 40px;
    }

    /* 🧾 HEADER */
    .header-section {
        border-left: 6px solid var(--azul);
        padding-left: 15px;
        margin-bottom: 25px;
    }

    .header-section h2 {
        font-weight: 600;
        color: var(--verde);
    }

    .header-section p {
        color: #6b7b83;
        font-size: 0.9rem;
    }

    /* ⚠️ ALERTAS */
    .alert-danger {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
        padding: 1rem;
        border-radius: 10px;
    }

    /* 📋 TABLAS */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
    }

    thead th {
        background: var(--verde);
        color: #fff;
        text-align: center;
        padding: 10px;
        font-weight: 500;
        border: none;
    }

    tbody td {
        border: 1px solid var(--gris-borde);
        padding: 10px;
        vertical-align: middle;
        background: #fff;
    }

    tr:nth-child(even) td {
        background: #f8fbfa;
    }

    /* 🔘 CAMPOS */
    input[type="text"],
    textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid var(--gris-borde);
        border-radius: 8px;
        transition: all 0.3s ease;
        background-color: #fafafa;
    }

    input[type="text"]:focus,
    textarea:focus {
        border-color: var(--azul);
        outline: none;
        background-color: #fff;
        box-shadow: 0 0 0 2px rgba(57, 169, 0, 0.1);
    }

    /* 🟢 BOTONES */
    .btn {
        display: inline-block;
        font-weight: 500;
        border-radius: 10px;
        padding: 10px 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-success {
        background-color: var(--verde);
        color: #fff;
        box-shadow: 0 4px 10px rgba(57, 169, 0, 0.25);
    }

    .btn-success:hover {
        background-color: var(--verde-oscuro);
        transform: translateY(-2px);
    }

    .btn-outline-primary {
        background: #fff;
        border: 2px solid var(--azul);
        color: var(--azul);
        font-weight: 500;
    }

    .btn-outline-primary:hover {
        background: var(--azul);
        color: #fff;
        box-shadow: 0 4px 10px rgba(64, 100, 122, 0.25);
    }

    /* ❌ BOTÓN DE ELIMINAR */
    .btn-danger {
        background: #d9534f;
        color: #fff;
        border: none;
        padding: 6px 10px;
        border-radius: 6px;
    }

    .btn-danger:hover {
        background: #c9302c;
    }

    /* 🧩 SECCIONES */
    h5 {
        color: var(--azul);
        font-weight: 600;
        border-bottom: 2px solid var(--verde);
        padding-bottom: 5px;
        margin-bottom: 15px;
    }

    /* 🧊 EFECTO DE BLOQUES */
    .table-responsive {
        border: 1px solid var(--gris-borde);
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    /* 🗒️ OBSERVACIONES */
    textarea {
        resize: vertical;
        background-color: #fcfdfc;
    }

    /* 🧠 ERRORES */
    .text-danger {
        font-size: 0.85rem;
        color: #d93025;
    }

    /* 🔁 ANIMACIÓN SUAVE */
    .btn,
    input,
    textarea,
    table {
        transition: all 0.3s ease;
    }
</style>


<div class="container remision-container">
    <div class="header-section">
        <h2>🧾 Criterios de Aceptación o Rechazo de Muestras</h2>
        <p class="text-muted">
            LABORATORIO CLÍNICO DE DIAGNÓSTICO ANIMAL – LABCLIMAL – CAA
        </p>
    </div>

    {{-- 🔴 Mostrar errores globales --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <h5 class="mb-2">❌ Se encontraron los siguientes errores:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORMULARIO -->
    <form action="{{ route('remisiones.criterios.store', $recibe->id) }}" method="POST" class="criterios-form">
        @csrf
        <input type="hidden" name="remision_muestra_recibe_id" value="{{ $recibe->id }}">

        <!-- 🧩 Criterios -->
        <h5 class="mb-3 mt-4">🧩 Criterios de Aceptación o Rechazo</h5>
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th style="width: 50px;">N°</th>
                    <th>Criterios de Aceptación o Rechazo</th>
                    <th style="width: 60px;">Sí</th>
                    <th style="width: 60px;">No</th>
                    <th style="width: 140px;">Temp. °C</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($criterios as $i => $criterio)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $criterio->descripcion }}</td>
                        <td class="text-center">
                            <input type="checkbox" class="criterio-si" name="criterios[{{ $criterio->id }}][si]"
                                value="1" {{ old("criterios.$criterio->id.si") ? 'checked' : '' }}>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" class="criterio-no" name="criterios[{{ $criterio->id }}][no]"
                                value="1" {{ old("criterios.$criterio->id.no") ? 'checked' : '' }}>
                        </td>
                        <td>
                            <input type="text" class="form-control temperatura-input"
                                name="criterios[{{ $criterio->id }}][temperatura]"
                                value="{{ old("criterios.$criterio->id.temperatura") }}" placeholder="Ej: 23.5">
                            @error("criterios.$criterio->id.temperatura")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </td>
                        <td>
                            <input type="text" class="form-control"
                                name="criterios[{{ $criterio->id }}][observaciones]"
                                value="{{ old("criterios.$criterio->id.observaciones") }}"
                                placeholder="Observaciones...">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- 📦 Ítems -->
        <h5 class="mb-3 mt-5">📦 Detalle de Ítems de Ensayo</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="items-table">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width: 40px;">N°</th>
                        <th>ID Ítem</th>
                        <th>Tipo de Empaque</th>
                        <th>Cantidad Requerida<br>SI / NO</th>
                        <th style="width: 160px;">Temperatura de Recepción °C</th>
                        <th>Observaciones</th>
                        <th>Ítem Aceptado<br>SI / NO</th>
                        <th>Código Interno</th>
                        <th style="width: 60px;">❌</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    @php
                        // Creamos un arreglo base con todos los campos
                        $defaultItem = [
                            'id_item' => '',
                            'tipo_empaque' => '',
                            'cantidad_requerida' => '',
                            'temperatura' => '',
                            'observaciones' => '',
                            'aceptado' => '',
                            'codigo_interno' => '',
                        ];
                        // Cargamos los datos viejos o, si no hay, uno vacío
                        $items = old('items', [$defaultItem]);
                    @endphp

                    @foreach ($items as $i => $item)
                        @php
                            // Mezclamos los valores reales con los predeterminados
                            $item = array_merge($defaultItem, $item ?? []);
                        @endphp

                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td><input type="text" name="items[{{ $i }}][id_item]" class="form-control"
                                    value="{{ $item['id_item'] }}"></td>
                            <td><input type="text" name="items[{{ $i }}][tipo_empaque]"
                                    class="form-control" value="{{ $item['tipo_empaque'] }}"></td>

                            <!-- ✅ Cantidad Requerida -->
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <label>
                                        <input type="radio" name="items[{{ $i }}][cantidad_requerida]"
                                            value="si" {{ $item['cantidad_requerida'] === 'si' ? 'checked' : '' }}>
                                        Sí
                                    </label>
                                    <label>
                                        <input type="radio" name="items[{{ $i }}][cantidad_requerida]"
                                            value="no" {{ $item['cantidad_requerida'] === 'no' ? 'checked' : '' }}>
                                        No
                                    </label>
                                </div>
                                @error("items.$i.cantidad_requerida")
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </td>

                            <td><input type="text" name="items[{{ $i }}][temperatura]"
                                    class="form-control" value="{{ $item['temperatura'] }}"></td>
                            <td><input type="text" name="items[{{ $i }}][observaciones]"
                                    class="form-control" value="{{ $item['observaciones'] }}"></td>

                            <!-- ✅ Ítem Aceptado -->
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <label>
                                        <input type="radio" name="items[{{ $i }}][aceptado]"
                                            value="si" {{ $item['aceptado'] === 'si' ? 'checked' : '' }}> Sí
                                    </label>
                                    <label>
                                        <input type="radio" name="items[{{ $i }}][aceptado]"
                                            value="no" {{ $item['aceptado'] === 'no' ? 'checked' : '' }}> No
                                    </label>
                                </div>
                                @error("items.$i.aceptado")
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </td>

                            <td><input type="text" name="items[{{ $i }}][codigo_interno]"
                                    class="form-control" value="{{ $item['codigo_interno'] }}"></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-item">✖</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>



        <!-- Botón agregar -->
        <div class="text-start mb-4">
            <button type="button" id="add-item" class="btn btn-outline-primary">➕ Agregar Ítem</button>
        </div>

        <!-- Observaciones generales -->
        <div class="mt-4">
            <label for="observaciones_generales" class="form-label fw-bold">🗒️ Observaciones Generales:</label>
            <textarea name="observaciones_generales" id="observaciones_generales" class="form-control" rows="3">{{ old('observaciones_generales') }}</textarea>
        </div>

        <!-- Botón de guardado -->
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-success px-4">💾 Guardar Todo</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ✅ Solo números y punto decimal
        document.querySelectorAll('.temperatura-input').forEach(input => {
            input.addEventListener('input', e => {
                e.target.value = e.target.value.replace(/[^0-9.,]/g, '').replace(/,/g, '.');
            });
        });

        // ✅ Checkboxes exclusivos (SI / NO)
        document.querySelectorAll('input.criterio-si, input.criterio-no').forEach(chk => {
            chk.addEventListener('change', e => {
                const row = e.target.closest('tr');
                if (e.target.classList.contains('criterio-si') && e.target.checked) {
                    row.querySelector('.criterio-no').checked = false;
                } else if (e.target.classList.contains('criterio-no') && e.target.checked) {
                    row.querySelector('.criterio-si').checked = false;
                }
            });
        });

        // ✅ Agregar ítems dinámicos
        const itemsTable = document.querySelector('#items-body');
        const addItemBtn = document.querySelector('#add-item');

        addItemBtn.addEventListener('click', () => {
            const newIndex = itemsTable.querySelectorAll('tr').length;
            const newRow = `
            <tr>
                <td>${newIndex + 1}</td>
                <td><input type="text" name="items[${newIndex}][id_item]" class="form-control"></td>
                <td><input type="text" name="items[${newIndex}][empaque]" class="form-control"></td>
                <td class="text-center"><input type="checkbox" name="items[${newIndex}][cantidad_requerida]" value="si"></td>
                <td><input type="text" name="items[${newIndex}][temperatura]" class="form-control temperatura-input" placeholder="Ej: 5.0"></td>
                <td><input type="text" name="items[${newIndex}][observaciones]" class="form-control"></td>
                <td class="text-center"><input type="checkbox" name="items[${newIndex}][aceptado]" value="si"></td>
                <td><input type="text" name="items[${newIndex}][codigo_interno]" class="form-control"></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row">✖</button></td>
            </tr>
        `;
            itemsTable.insertAdjacentHTML('beforeend', newRow);
        });

        // ✅ Eliminar filas dinámicamente
        itemsTable.addEventListener('click', e => {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
                Array.from(itemsTable.querySelectorAll('tr')).forEach((tr, index) => {
                    tr.children[0].textContent = index + 1;
                });
            }
        });
    });
</script>
