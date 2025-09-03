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
        // 1) cargamos la remisión (envío) con las relaciones de persona y animales
        $remision = RemisionMuestraEnvio::with([
            'persona.direcciones',
            'persona.animales.especie',
            'persona.animales.raza',
            //'tiposMuestra', // opcional: si esta relación existe y funciona
            'remision_muestra_recibe' // coleccion de remision_muestra_recibe
        ])->findOrFail($id);

        // 2) Obtener los ids de remision_muestra_recibe asociados a esta remisión envío
        $remisionRecibeIds = DB::table('remision_muestra_recibe')
            ->where('muestra_enviada_id', $remision->id)
            ->pluck('id')
            ->toArray();

        // 3) Si no hay remisiones recibidas, no hay técnicas asociadas
        $tecnicas = collect();
        if (!empty($remisionRecibeIds)) {
            /*
         *  Ajusta el nombre 'tecnicas_muestra' si en tu BD la tabla de técnicas se llama distinto.
         *  Hacemos join para sacar nombre y opcionalmente contar cuántas veces aparece (útil para mostrar).
         */
            $tecnicas = DB::table('muestra_recibe_tecnica')
                ->whereIn('muestra_recibe_id', $remisionRecibeIds)
                ->join('tecnicas_muestra', 'muestra_recibe_tecnica.tecnica_id', '=', 'tecnicas_muestra.id')
                ->select('tecnicas_muestra.id', 'tecnicas_muestra.nombre', DB::raw('COUNT(*) as veces'))
                ->groupBy('tecnicas_muestra.id', 'tecnicas_muestra.nombre')
                ->get();
        }

        // 4) Muestras asociadas (pivot remision_tipo_muestra)
    $muestras = DB::table('remision_tipo_muestra')
        ->where('remision_id', $remision->id)
        ->join('tipos_muestra', 'remision_tipo_muestra.tipo_muestra_id', '=', 'tipos_muestra.id')
        ->select(
            'tipos_muestra.id',
            'tipos_muestra.nombre',
            'remision_tipo_muestra.cantidad_muestra',
            'remision_tipo_muestra.refrigeracion',
            'remision_tipo_muestra.observaciones'
        )
        ->get();

    // 5) pasar todo a la vista
    return view('dashboard.show', [
        'remision' => $remision,
        'tecnicas' => $tecnicas, // colección de técnicas
        'muestras' => $muestras, // colección de muestras
    ]);
    }




    public function showForm()
    {

        $tiposMuestra = TiposMuestra::all();
        $clientes = Persona::all();


        return view('remisiones.remision_enviada', compact('tiposMuestra', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'cliente_id' => 'required|exists:personas,id',
            'tipos_muestra' => 'required|array',
        ]);

        $remision = RemisionMuestraEnvio::create([
            'fecha' => now(),
            'cliente_id' => $request->cliente_id,
            'observaciones' => $request->observaciones,
        ]);

        foreach ($request->tipos_muestra as $tipoId => $datos) {
            if (!empty($datos['activo']) && isset($datos['cantidad'], $datos['refrigeracion'])) {
                $remision->tiposMuestras()->attach($tipoId, [
                    'cantidad_muestra' => $datos['cantidad'],
                    'refrigeracion' => $datos['refrigeracion'],
                    'observaciones' => $datos['observaciones'] ?? null,
                ]);
            }
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
        $tecnicas = TecnicasMuestra::all();

        return view('remisiones.remision_recibida', [
            'remision' => $remision,
            'tecnicas' => $tecnicas,
        ]);
    }



    public function storeRecibido(Request $request)
    {
        $request->validate([
            'muestra_enviada_id' => 'required|exists:remision_muestra_envio,id',
            'tecnicas' => 'required|array',
            'tecnicas.*' => 'exists:tecnicas_muestra,id',
        ]);

        $muestraRecibe = RemisionMuestraRecibe::create([
            'muestra_enviada_id' => $request->muestra_enviada_id,
            'responsable_id' => auth()->id(),
            'fecha' => now(),
        ]);

        $muestraRecibe->tecnicas()->attach($request->tecnicas);

        return redirect()->route('dashboard')->with('success', 'Recepción de muestra registrada correctamente.');
    }
}
