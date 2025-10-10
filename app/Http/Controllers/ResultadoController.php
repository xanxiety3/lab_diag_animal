<?php

namespace App\Http\Controllers;

use App\Models\Animale;
use App\Models\MuestraRecibeTecnica;
use App\Models\RemisionMuestraEnvio;
use App\Models\RemisionMuestraRecibe;
use App\Models\Resultado;
use App\Models\ResultadoBearman;
use App\Models\ResultadoCoproFresco;
use App\Models\ResultadoHemograma;
use App\Models\ResultadoMacMaster;
use App\Models\TecnicasMuestra;
use App\Models\TiposMuestra;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ResultadoController extends Controller
{


    public function dashboardResultados()
    {
        // 1️⃣ Muestras por tipo de ubicación
        $porUbicacion = DB::table('remision_muestra_recibe')
            ->join('remision_muestra_envio', 'remision_muestra_envio.id', '=', 'remision_muestra_recibe.muestra_enviada_id')
            ->join('direcciones', 'direcciones.cliente_id', '=', 'remision_muestra_envio.cliente_id')
            ->join('tipos_ubicacion', 'tipos_ubicacion.id', '=', 'direcciones.tipo_ubicacion_id')
            ->select('tipos_ubicacion.nombre as ubicacion', DB::raw('COUNT(remision_muestra_recibe.id) as total'))
            ->groupBy('tipos_ubicacion.nombre')
            ->orderByDesc('total')
            ->limit(6)
            ->get();


        // 2️⃣ Muestras por tipo
        $muestrasPorTipo = DB::table('remision_tipo_muestra')
            ->join('tipos_muestra', 'remision_tipo_muestra.tipo_muestra_id', '=', 'tipos_muestra.id')
            ->select('tipos_muestra.nombre as tipo', DB::raw('COUNT(remision_tipo_muestra.id) as total'))
            ->groupBy('tipos_muestra.nombre')
            ->orderByDesc('total')
            ->get();

        // 6️⃣ Tendencia de resultados registrados (últimos 6 meses)
        $resultadosTendencia = DB::table('muestra_recibe_tecnica')
            ->selectRaw('MONTH(created_at) as mes, COUNT(   *) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();


        // 7️⃣ Distribución por tipo de técnica
        $resultadosPorTecnica = DB::table('animal_tecnica_resultado')
            ->join('tecnicas_muestra', 'animal_tecnica_resultado.tecnica_id', '=', 'tecnicas_muestra.id')
            ->select('tecnicas_muestra.nombre as tecnica', DB::raw('COUNT(animal_tecnica_resultado.id) as total'))
            ->groupBy('tecnicas_muestra.nombre')
            ->orderByDesc('total')
            ->get();


        // 8️⃣ Porcentaje de muestras rechazadas vs aceptadas
        $rechazadasVsAceptadas = DB::table('remision_muestra_recibe')
            ->select(
                DB::raw('SUM(CASE WHEN rechazada = 1 THEN 1 ELSE 0 END) as rechazadas'),
                DB::raw('SUM(CASE WHEN rechazada = 0 THEN 1 ELSE 0 END) as aceptadas')
            )
            ->first();
        return view('dashboard.dashboard_resultados', [
            'porUbicacion' => $porUbicacion,
            'muestrasPorTipo' => $muestrasPorTipo,
            'resultadosTendencia' => $resultadosTendencia,
            'resultadosPorTecnica' => $resultadosPorTecnica,
            'rechazadas' => $rechazadasVsAceptadas,
        ]);
    }






    public function elegirMuestra($remisionEnvioId)
    {
        // Traemos la remisión enviada con sus relaciones
        $remision = RemisionMuestraEnvio::with([
            'tiposMuestras',
            'remision_muestra_recibe.tecnicas',
            'remision_muestra_recibe.responsable'
        ])->findOrFail($remisionEnvioId);

        // Normalizar remisionRecibe: si es colección, tomar first(), si es modelo dejarlo
        $remisionRecibe = $remision->remision_muestra_recibe;
        if ($remisionRecibe instanceof \Illuminate\Database\Eloquent\Collection) {
            $remisionRecibe = $remisionRecibe->first();
        }

        // Muestras de la remisión
        $muestras = $remision->tiposMuestras ?? collect();

        return view('dashboard.elegir_muestra', compact('remision', 'remisionRecibe', 'muestras'));
    }



    // en ResultadoController (o donde tengas elegirTecnica)
    public function elegirTecnica($remisionEnvioId)
    {
        // 1) traemos la remisión (igual que antes)
        $remision = RemisionMuestraEnvio::with(['tiposMuestras', 'persona', 'remision_muestra_recibe'])
            ->findOrFail($remisionEnvioId);

        // 2) normalizar la recepción (puede ser colección o single)
        $remisionRecibe = $remision->remision_muestra_recibe;
        if ($remisionRecibe instanceof \Illuminate\Database\Eloquent\Collection) {
            $remisionRecibe = $remisionRecibe->first();
        }

        // 3) muestras desde el pivot (igual que tenías)
        $muestras = $remision->tiposMuestras()->withPivot([
            'cantidad_muestra',
            'refrigeracion',
            'observaciones'
        ])->get();

        // 4) técnicas: por defecto vacío
        $tecnicas = collect();

        if ($remisionRecibe) {
            // OJO: pedimos explícitamente el id del pivot para poder usarlo
            $tecnicas = $remisionRecibe->tecnicas()->withPivot('id')->get();

            // 5) sacar todos los pivot ids (muestra_recibe_tecnica.id)
            $pivotIds = $tecnicas->pluck('pivot.id')->filter()->unique()->values()->all();

            if (!empty($pivotIds)) {
                // 6) consultar en una sola query qué pivot ids ya tienen resultado
                $existingPivotIds = \App\Models\Resultado::whereIn('muestra_recibe_tecnica_id', $pivotIds)
                    ->pluck('muestra_recibe_tecnica_id')
                    ->map(fn($v) => (int) $v)
                    ->toArray();
            } else {
                $existingPivotIds = [];
            }

            // 7) marcar cada técnica con una propiedad dinámica
            foreach ($tecnicas as $t) {
                $pid = isset($t->pivot->id) ? (int)$t->pivot->id : null;
                $t->tiene_resultado = $pid ? in_array($pid, $existingPivotIds, true) : false;
            }
        }

        return view('dashboard.elegir_tecnica', compact('remision', 'muestras', 'remisionRecibe', 'tecnicas'));
    }





    public function asignarAnimales($remisionId, $tecnicaId)
    {
        // Buscar la remisión enviada
        $remision = RemisionMuestraEnvio::findOrFail($remisionId);

        // Buscar la recepción asociada (si existe)
        $remisionRecibe = $remision->remision_muestra_recibe;
        if ($remisionRecibe instanceof \Illuminate\Database\Eloquent\Collection) {
            $remisionRecibe = $remisionRecibe->first();
        }

        // Obtener la técnica
        $tecnica = TecnicasMuestra::findOrFail($tecnicaId);

        // Animales asociados a la recepción
        $animales = $remisionRecibe ? $remisionRecibe->animales : collect();

        return view('dashboard.asignar_animales', compact('remision', 'remisionRecibe', 'tecnica', 'animales'));
    }


    public function guardarAnimales($tecnicaId, $remisionRecibeId, Request $request)
    {
        $data = $request->validate([
            'animales'   => 'required|array',
            'animales.*' => 'exists:animales,id'
        ]);

        foreach ($data['animales'] as $animalId) {
            DB::table('animal_tecnica_resultado')->insert([
                'remision_muestra_recibe_id' => $remisionRecibeId,
                'tecnica_id'                 => $tecnicaId,
                'animal_id'                  => $animalId,
                'created_at'                 => now(),
                'updated_at'                 => now(),
            ]);
        }

        return redirect()
            ->route('resultados.create', [
                'remisionRecibe' => $remisionRecibeId,
                'tecnica'        => $tecnicaId
            ])
            ->with('success', '✅ Animales asignados correctamente.');
    }



    public function createResultado($remisionRecibeId, $tecnicaId)
    {
        $tecnica = TecnicasMuestra::findOrFail($tecnicaId);

        // Animales filtrados por técnica y remisión
        $animales = Animale::whereHas('tecnicasAsignadas', function ($q) use ($remisionRecibeId, $tecnicaId) {
            $q->where('remision_muestra_recibe_id', $remisionRecibeId)
                ->where('tecnica_id', $tecnicaId);
        })
            ->with(['tecnicasAsignadas' => function ($q) use ($remisionRecibeId, $tecnicaId) {
                $q->where('remision_muestra_recibe_id', $remisionRecibeId)
                    ->where('tecnica_id', $tecnicaId);
            }])
            ->get();

        $formato = $tecnica->formato;

        return view("resultados.formularios.$formato", compact('remisionRecibeId', 'tecnica', 'animales'));
    }


    public function storeResultadoMultiple(Request $request, $remisionRecibeId, $tecnicaId)
    {


        $tecnica = TecnicasMuestra::findOrFail($tecnicaId);


        foreach ($request->codigo_interno as $animalId => $codigo) {
            // 🔹 Buscar el pivot correcto según remisión y técnica
            $pivot = \App\Models\MuestraRecibeTecnica::where('muestra_recibe_id', $remisionRecibeId)
                ->where('tecnica_id', $tecnicaId)
                ->firstOrFail(); // Si no existe, lanza error para no romper FK

            // 1. Crear resultado base
            $resultado = Resultado::create([
                'usuario_id'                => auth()->id(),
                'estado'                    => 'finalizado',
                'muestra_recibe_tecnica_id' => $pivot->id, // ✅ id correcto
                'animal_id'                 => $animalId,
            ]);

            // 2. Crear resultado específico según el formato
            switch ($tecnica->formato) {
                case 'copro_fresco':
                    ResultadoCoproFresco::create([
                        'resultado_id' => $resultado->id,
                        'codigo_interno' => $codigo,
                        'sexo' => $request->sexo[$animalId],
                        'especie' => $request->especie[$animalId],
                        'color' => $request->color[$animalId],
                        'consistencia' => $request->consistencia[$animalId],
                        'moco' => $request->moco[$animalId],
                        'sangre' => $request->sangre[$animalId],
                        'celulas_epiteliales' => $request->celulas_epiteliales[$animalId],
                        'celulas_vegetales' => $request->celulas_vegetales[$animalId],
                        'huevos' => $request->huevos[$animalId],
                        'quistes' => $request->quistes[$animalId],
                        'levaduras' => $request->levaduras[$animalId],
                        'otros' => $request->otros[$animalId],
                        'observaciones' => $request->observaciones[$animalId],
                    ]);
                    break;

                case 'mac_master':
                    ResultadoMacMaster::create([
                        'resultado_id'       => $resultado->id,
                        'codigo_interno'     => $codigo,
                        'cantidad_muestra'   => $request->cantidad_muestra[$animalId],
                        'solucion_flotacion' => $request->solucion_flotacion[$animalId],
                        'strongylida_c1'     => $request->strongylida_c1[$animalId],
                        'strongylida_c2'     => $request->strongylida_c2[$animalId],
                        'strongylus_c1'      => $request->strongylus_c1[$animalId],
                        'strongylus_c2'      => $request->strongylus_c2[$animalId],
                        'moniezia_c1'        => $request->moniezia_c1[$animalId],
                        'moniezia_c2'        => $request->moniezia_c2[$animalId],
                        'eimeria_c1'         => $request->eimeria_c1[$animalId],
                        'eimeria_c2'         => $request->eimeria_c2[$animalId],
                        'observaciones'      => $request->observaciones[$animalId],
                    ]);
                    break;

                case 'bearman':
                    ResultadoBearman::create([
                        'resultado_id'     => $resultado->id,
                        'codigo_interno'   => $codigo,
                        'codigo_solicitud' => $request->codigo_solicitud[$animalId],
                        'fecha_analisis'   => $request->fecha_analisis[$animalId],
                        'cantidad_muestra' => $request->cantidad_muestra[$animalId],
                        'larvas'           => $request->larvas[$animalId],
                        'observaciones'    => $request->observaciones[$animalId],
                    ]);
                    break;

                case 'hemograma':
                    ResultadoHemograma::create([
                        'resultado_id' => $resultado->id,
                        'codigo_interno' => $codigo,
                        'especie' => $request->especie[$animalId],
                        'sexo' => $request->sexo[$animalId],
                        'hb' => $request->hb[$animalId],
                        'hto' => $request->hto[$animalId],
                        'leucocitos' => $request->leucocitos[$animalId],
                        'neu' => $request->neu[$animalId],
                        'eos' => $request->eos[$animalId],
                        'bas' => $request->bas[$animalId],
                        'lin' => $request->lin[$animalId],
                        'mon' => $request->mon[$animalId],
                        'plaquetas' => $request->plaquetas[$animalId],
                        'vcm' => $request->vcm[$animalId],
                        'hcm' => $request->hcm[$animalId],
                        'chcm' => $request->chcm[$animalId],
                        'hemoparasitos' => $request->hemoparasitos[$animalId],
                        'observaciones' => $request->observaciones[$animalId],
                    ]);
                    break;
            }
        }

        $remision = RemisionMuestraRecibe::find($remisionRecibeId);

        if ($remision->todasTecnicasConResultado()) {
            $remision->update(['registro_resultado' => 1]);
        }


        return redirect()->route('dashboard')
            ->with('success', '✅ Resultados registrados correctamente.');
    }






    // public function resultadosIndex($remisionRecibeId, $tecnicaId)
    // {
    //     $asignaciones = DB::table('remision_tecnica_animal')
    //         ->where('remision_recibe_id', $remisionRecibeId)
    //         ->where('tecnica_id', $tecnicaId)
    //         ->join('animales', 'remision_tecnica_animal.animal_id', '=', 'animales.id')
    //         ->select('remision_tecnica_animal.id as asignacion_id', 'animales.nombre as animal')
    //         ->get();

    //     return view('dashboard.registrar_resultado_global', compact('asignaciones'));
    // }

    // public function guardarResultados(Request $request, $remisionRecibeId, $tecnicaId)
    // {
    //     $resultados = $request->input('resultados', []);
    //     // formato esperado: [ asignacion_id => "texto resultado", ... ]

    //     foreach ($resultados as $asignacionId => $valor) {
    //         DB::table('resultados')->updateOrInsert(
    //             ['remision_tecnica_animal_id' => $asignacionId],
    //             [
    //                 'observaciones' => $valor,
    //                 'estado' => 'registrado',
    //                 'updated_at' => now(),
    //                 'created_at' => now(),
    //             ]
    //         );
    //     }

    //     return redirect()->route('show.remision ', $remisionRecibeId)
    //         ->with('success', 'Resultados guardados correctamente.');
    // }
}
