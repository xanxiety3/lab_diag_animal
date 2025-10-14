<?php

namespace App\Http\Controllers;

use App\Models\CriteriosAceptacion;
use App\Models\MuestraRecibeTecnica;
use App\Models\Persona;
use App\Models\RemisionMuestraEnvio;
use App\Models\RemisionMuestraRecibe;
use App\Models\RemisionRecibeCriterio;
use App\Models\RemisionRecibeItem;
use App\Models\TecnicasMuestra;
use App\Models\TiposMuestra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RemisionesController extends Controller
{



    public function show($id)
    {
        // $id = muestra_enviada_id (id de remision_muestra_envio)
        $remision = RemisionMuestraEnvio::with([
            'persona.direcciones',
            'persona.animales.especie',
            'persona.animales.raza',
            'tiposMuestras',                           // pivot remision_tipo_muestra
            'remision_muestra_recibe.responsable',     // responsable (usuario) en remision recibida
            'remision_muestra_recibe.tecnicas'         // técnicas asociadas a la remisión recibida
        ])->findOrFail($id);

        // Normalizar remisionRecibe: si es colección tomar first(), si es modelo dejarlo
        $remisionRecibe = $remision->remision_muestra_recibe;
        if ($remisionRecibe instanceof \Illuminate\Database\Eloquent\Collection) {
            $remisionRecibe = $remisionRecibe->first();
        }

        $muestras = $remision->tiposMuestras ?? collect();

        return view('dashboard.show', compact('remision', 'remisionRecibe', 'muestras'));
    }






    public function showForm(Request $request)
    {
        $tiposMuestra = TiposMuestra::all();
        $clientes = Persona::all();

        // Cliente preseleccionado desde la redirección
        $selectedCliente = $request->get('cliente_id');

        return view('remisiones.remision_enviada', compact('tiposMuestra', 'clientes', 'selectedCliente'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'cliente_id' => 'required|exists:personas,id',
            'tipos_muestra' => 'required|array|min:1',
            'tipos_muestra.*.activo' => 'nullable|boolean',
        ]);

        $cliente = Persona::with('animales')->findOrFail($request->cliente_id);
        $cantidadAnimales = $cliente->animales->count();

        if ($cantidadAnimales === 0) {
            return back()->withErrors(['cliente_id' => 'El cliente no tiene animales registrados.'])->withInput();
        }

        $tiposSeleccionados = collect($request->tipos_muestra)->filter(fn($m) => isset($m['activo']) && $m['activo'] == 1);

        if ($tiposSeleccionados->isEmpty()) {
            return back()->withErrors(['tipos_muestra' => 'Debe seleccionar al menos un tipo de muestra.'])->withInput();
        }

        // Validar coherencia entre cantidad de muestra y cantidad de animales
        foreach ($tiposSeleccionados as $tipoId => $datos) {
            $cantidad = intval($datos['cantidad'] ?? 0);
            if ($cantidad < 1) {
                return back()->withErrors(["tipos_muestra.$tipoId.cantidad" => "Debe ingresar una cantidad válida para la muestra seleccionada."])->withInput();
            }

            if ($cantidad > $cantidadAnimales) {
                return back()->withErrors([
                    "tipos_muestra.$tipoId.cantidad" =>
                    "La cantidad de muestras no puede superar el número de animales registrados ({$cantidadAnimales})."
                ])->withInput();
            }
        }

        // Si pasa las validaciones, guardamos la remisión
        $remision = RemisionMuestraEnvio::create([
            'fecha' => $request->fecha,
            'cliente_id' => $request->cliente_id,
            'observaciones' => $request->observaciones,
        ]);

        foreach ($tiposSeleccionados as $tipoId => $datos) {
            $remision->tiposMuestras()->attach($tipoId, [
                'cantidad_muestra' => $datos['cantidad'],
                'refrigeracion' => $datos['refrigeracion'] ?? 0,
                'observaciones' => $datos['observaciones'] ?? null,
            ]);
        }

        return redirect()->route('formulario.recibida', ['remision_id' => $remision->id])
            ->with('success', 'Remisión registrada correctamente.');
    }




    public function showFormRecibido(Request $request)
    {
        $remisionId = $request->input('remision_id');

        // Obtener la remisión enviada específica
        $remision = RemisionMuestraEnvio::findOrFail($remisionId);

        // Extraer los IDs de los tipos de muestra seleccionados en esa remisión
        $tiposSeleccionados = $remision->tiposMuestras->pluck('id');

        // Obtener solo las técnicas que correspondan a esos tipos de muestra
        $tecnicas = TecnicasMuestra::with('tipos_muestra')
            ->whereIn('tipo_muestra_id', $tiposSeleccionados)
            ->get();

        return view('remisiones.remision_recibida', [
            'remision' => $remision,
            'tecnicas' => $tecnicas,
        ]);
    }

    public function storeRecibido(Request $request)
    {
        $request->validate([
            'muestra_enviada_id' => 'required|exists:remision_muestra_envio,id',
            'tecnicas' => 'required|array|min:1',
        ], [
            'muestra_enviada_id.required' => 'Falta la remisión enviada.',
            'tecnicas.required' => 'Debe seleccionar al menos una técnica.',
        ]);

        // ✅ Filtrar solo las técnicas que tengan animales o cantidad válida
        $tecnicasFiltradas = collect($request->tecnicas)
            ->filter(function ($t) {
                // Decodificar animales si vienen en string
                $animales = $t['animales'] ?? [];
                if (is_string($animales)) {
                    $animales = json_decode($animales, true) ?? [];
                }

                return !empty($animales) || (!empty($t['cantidad']) && $t['cantidad'] > 0);
            })
            ->all();

        if (empty($tecnicasFiltradas)) {
            return back()->withErrors([
                'tecnicas' => 'Debe seleccionar al menos una técnica válida con animales o cantidad.',
            ]);
        }

        DB::beginTransaction();

        try {
            $muestraRecibe = RemisionMuestraRecibe::create([
                'muestra_enviada_id' => $request->muestra_enviada_id,
                'responsable_id' => auth()->id(),
                'fecha' => now(),
            ]);

            foreach ($tecnicasFiltradas as $tecnicaData) {
                $tecnicaId = $tecnicaData['id'] ?? null;
                $cantidad = (int) ($tecnicaData['cantidad'] ?? 1); // ✅ valor por defecto 1

                if (!$tecnicaId) continue;

                // ✅ Decodificar animales si vienen en JSON
                $animales = $tecnicaData['animales'] ?? [];
                if (is_string($animales)) {
                    $animales = json_decode($animales, true) ?? [];
                }

                // Obtener valor unitario de la técnica
                $valorUnitario = TecnicasMuestra::find($tecnicaId)?->valor_unitario ?? 0;

                // ✅ Registrar técnica recibida (pivot con cantidad y valor)
                $muestraRecibe->tecnicas()->attach($tecnicaId, [
                    'cantidad' => $cantidad,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // ✅ Insertar animales asociados (si existen)
                foreach ($animales as $animalId) {
                    DB::table('animal_tecnica_resultado')->insert([
                        'remision_muestra_recibe_id' => $muestraRecibe->id,
                        'tecnica_id' => $tecnicaId,
                        'animal_id' => $animalId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            // Redirigir a la vista de criterios
            return redirect()->route('remisiones.criterios.create', $muestraRecibe->id)
                ->with('success', 'Recepción de muestra registrada correctamente. Ahora complete los criterios.');
        } catch (\Throwable $e) {
            DB::rollBack();

            // ✅ Registrar error para depuración si estás en modo debug
            report($e);

            return back()->withErrors([
                'error' => 'Ocurrió un error al guardar: ' . $e->getMessage(),
            ]);
        }
    }


    // Mostrar formulario para criterios de una remisión recibida
    public function formCriterios($recibeId)
    {
        $recibe = RemisionMuestraRecibe::findOrFail($recibeId);
        $criterios = CriteriosAceptacion::all(); // todos los criterios predefinidos

        return view('remisiones.criterios_aceptacion', compact('recibe', 'criterios'));
    }

    public function storeCriteriosAceptacion(Request $request, $recibe)
    {
        $recibe = RemisionMuestraRecibe::findOrFail($recibe);

        $errores = [];

        /* ---------------------------------------------------------
     * 🔍 VALIDACIÓN DE CRITERIOS
     * --------------------------------------------------------- */
        if (!$request->filled('criterios') || !is_array($request->criterios) || count($request->criterios) === 0) {
            $errores['criterios'] = 'Debe diligenciar al menos un criterio.';
        } else {
            foreach ($request->criterios as $criterioId => $data) {
                $si = isset($data['si']) ? (bool)$data['si'] : false;
                $no = isset($data['no']) ? (bool)$data['no'] : false;
                $temperatura = $data['temperatura'] ?? null;

                if (!$si && !$no) {
                    $errores["criterios.$criterioId"] = "Debe marcar SI o NO en el criterio #$criterioId.";
                }

                if ($si && $no) {
                    $errores["criterios.$criterioId"] = "No puede marcar SI y NO al mismo tiempo en el criterio #$criterioId.";
                }

                if ($temperatura !== null && $temperatura !== '' && !is_numeric($temperatura)) {
                    $errores["criterios.$criterioId.temperatura"] = "La temperatura del criterio #$criterioId debe ser numérica.";
                }
            }
        }

        /* ---------------------------------------------------------
     * 📦 VALIDACIÓN DE ÍTEMS
     * --------------------------------------------------------- */
        if (!$request->filled('items') || !is_array($request->items) || count($request->items) === 0) {
            $errores['items'] = 'Debe registrar al menos un ítem.';
        } else {
            foreach ($request->items as $index => $itemData) {
                $numero = $index + 1;

                if (empty($itemData['id_item'])) {
                    $errores["items.$index.id_item"] = "El campo ID Ítem en la fila #$numero es obligatorio.";
                }

                if (empty($itemData['tipo_empaque'])) {
                    $errores["items.$index.tipo_empaque"] = "Debe ingresar el Tipo de Empaque en la fila #$numero.";
                }

                if (!empty($itemData['temperatura']) && !is_numeric($itemData['temperatura'])) {
                    $errores["items.$index.temperatura"] = "La temperatura en la fila #$numero debe ser un valor numérico.";
                }

                $si = $itemData['cantidad_requerida'] ?? null;
                $aceptado = $itemData['aceptado'] ?? null;

                if ($si !== 'si' && $si !== 'no') {
                    $errores["items.$index.cantidad_requerida"] = "Debe indicar SI o NO en 'Cantidad Requerida' de la fila #$numero.";
                }

                if ($aceptado !== 'si' && $aceptado !== 'no') {
                    $errores["items.$index.aceptado"] = "Debe indicar SI o NO en 'Ítem Aceptado' de la fila #$numero.";
                }
            }
        }

        /* ---------------------------------------------------------
     * ❌ SI HAY ERRORES, RETORNAR A LA VISTA
     * --------------------------------------------------------- */
        if (!empty($errores)) {
            return back()->withErrors($errores)->withInput();
        }

        /* ---------------------------------------------------------
     * ✅ GUARDAR CRITERIOS
     * --------------------------------------------------------- */
        foreach ($request->criterios as $criterioId => $data) {
            RemisionRecibeCriterio::updateOrCreate(
                [
                    'remision_muestra_recibe_id' => $recibe->id,
                    'criterio_id' => $criterioId,
                ],
                [
                    'si' => isset($data['si']),
                    'no' => isset($data['no']),
                    'temperatura' => $data['temperatura'] ?? null,
                    'observaciones' => $data['observaciones'] ?? null,
                ]
            );
        }

        /* ---------------------------------------------------------
     * ✅ GUARDAR ÍTEMS
     * --------------------------------------------------------- */
        foreach ($request->items as $itemData) {
            RemisionRecibeItem::updateOrCreate(
                [
                    'remision_muestra_recibe_id' => $recibe->id,
                    'id_item' => $itemData['id_item'],
                ],
                [
                    'tipo_empaque' => $itemData['tipo_empaque'],
                    'cantidad_requerida' => $itemData['cantidad_requerida'],
                    'temperatura' => $itemData['temperatura'] ?? null,
                    'observaciones' => $itemData['observaciones'] ?? null,
                    'aceptado' => $itemData['aceptado'],
                    'codigo_interno' => $itemData['codigo_interno'] ?? null,
                ]
            );
        }

        return redirect()->route('dashboard')->with('success', 'Criterios e ítems guardados correctamente.');
    }
}
