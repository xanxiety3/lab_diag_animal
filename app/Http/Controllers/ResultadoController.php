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
use App\Models\ResultadoMcMaster;
use App\Models\TecnicasMuestra;
use App\Models\TiposMuestra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultadoController extends Controller
{


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



    public function elegirTecnica($remisionEnvioId)
    {

        $remision = RemisionMuestraEnvio::with([
            'tiposMuestras',
            'persona',
            'remision_muestra_recibe.tecnicas'
        ])->findOrFail($remisionEnvioId);

        // 2️⃣ Muestras (desde el pivote remision_tipo_muestra)
        $muestras = $remision->tiposMuestras()->withPivot([
            'cantidad_muestra',
            'refrigeracion',
            'observaciones'
        ])->get();

        // 3️⃣ Verificar si tiene recepción (remision_muestra_recibe)
        $remisionRecibe = $remision->remision_muestra_recibe;

        // Normalizamos por si acaso es colección
        if ($remisionRecibe instanceof \Illuminate\Database\Eloquent\Collection) {
            $remisionRecibe = $remisionRecibe->first();
        }

        // 4️⃣ Técnicas asociadas a la recepción (si existe)
        $tecnicas = $remisionRecibe
            ? $remisionRecibe->tecnicas
            : collect(); // colección vacía si no hay recepción

        return view('dashboard.elegir_tecnica', compact(
            'remision',
            'muestras',
            'remisionRecibe',
            'tecnicas'
        ));
    }






    public function asignarAnimales($remisionId, $tecnicaId)
    {
        // Buscar la remisión enviada
        $remision = RemisionMuestraEnvio::with('persona.animales')->findOrFail($remisionId);

        // Buscar la recepción asociada (si existe)
        $remisionRecibe = $remision->remision_muestra_recibe;

        // Obtener la técnica
        $tecnica = TecnicasMuestra::findOrFail($tecnicaId);

        // Animales del propietario
        $animales = $remision->persona->animales ?? collect();

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
        // 1. Obtener la técnica
        $tecnica = TecnicasMuestra::findOrFail($tecnicaId);

        // 2. Obtener solo los animales asignados a esta técnica en esta remisión
        $animales = Animale::whereHas('tecnicasAsignadas', function ($q) use ($remisionRecibeId, $tecnicaId) {
            $q->where('remision_muestra_recibe_id', $remisionRecibeId)
                ->where('tecnica_id', $tecnicaId);
        })->get();

        // 3. Identificar el formato
        $formato = $tecnica->formato;

        // 4. Redirigir a la vista adecuada, pasando los animales filtrados
        return view("resultados.formularios.$formato", compact('remisionRecibeId', 'tecnica', 'animales'));
    }


    public function storeResultadoMultiple(Request $request, $remisionRecibeId, $tecnicaId)
    {
        $tecnica = TecnicasMuestra::findOrFail($tecnicaId);

        foreach ($request->codigo_interno as $animalId => $codigo) {
            // Buscar el pivot_id enviado desde el formulario (puedes pasarlo como input hidden)
            $pivotId = $request->pivot_id[$animalId];

            // 1. Crear resultado base
            $resultado = Resultado::create([
                'usuario_id' => auth()->id(),
                'estado' => 'finalizado',
                'muestra_recibe_tecnica_id' => $pivotId, // ✅ ahora sí es correcto
                'animal_id' => $animalId,
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

                    // Repetir para otros formatos: mcmaster, bearman, hemograma
            }
        }

        return redirect()->route('resultados.index')
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
