<?php

namespace App\Http\Controllers;

use App\Models\MuestraRecibeTecnica;
use App\Models\Persona;
use App\Models\RemisionMuestraEnvio;
use App\Models\RemisionMuestraRecibe;
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

        // Obtener todas las técnicas disponibles
        $tecnicas = TecnicasMuestra::with('tipos_muestra')->get();


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

        return redirect()->route('dashboard')->with('success', 'Recepción de muestra registrada correctamente.');
    } catch (\Throwable $e) {
        DB::rollBack();

        // ✅ Registrar error para depuración si estás en modo debug
        report($e);

        return back()->withErrors([
            'error' => 'Ocurrió un error al guardar: ' . $e->getMessage(),
        ]);
    }
}

}
