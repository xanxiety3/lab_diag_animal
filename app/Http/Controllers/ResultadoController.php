<?php

namespace App\Http\Controllers;

use App\Models\MuestraRecibeTecnica;
use App\Models\RemisionMuestraRecibe;
use App\Models\TecnicasMuestra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultadoController extends Controller
{
    public function create($remisionId)
    {
        $remision = RemisionMuestraRecibe::with('tecnicas')->findOrFail($remisionId);

        if ($remision->rechazada) {
            return redirect()->back()->with('error', 'No se pueden registrar resultados en una muestra rechazada.');
        }

        return view('dashboard.resultados', compact('remision'));
    }

   public function asignarAnimales($remisionRecibeId, $tecnicaId)
{
    $remisionRecibe = RemisionMuestraRecibe::with('remision_muestra_envio.persona.animales.especie')
        ->findOrFail($remisionRecibeId);

    $tecnica  = MuestraRecibeTecnica::findOrFail($tecnicaId);

    $persona = $remisionRecibe->remision_muestra_envio->persona;
    $animales = $persona->animales ?? collect();

    return view('dashboard.asignar_animales', compact('remisionRecibe', 'tecnica', 'animales', 'persona'));
}


    public function guardarAnimales(Request $request, $remisionRecibeId, $tecnicaId)
    {
        $animalIds = $request->input('animales', []);

        foreach ($animalIds as $animalId) {
            DB::table('remision_tecnica_animal')->updateOrInsert(
                [
                    'remision_recibe_id' => $remisionRecibeId,
                    'tecnica_id' => $tecnicaId,
                    'animal_id' => $animalId,
                ],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        return redirect()->route('tecnicas.resultados.index', [$remisionRecibeId, $tecnicaId])
            ->with('success', 'Animales asignados correctamente.');
    }


    public function resultadosIndex($remisionRecibeId, $tecnicaId)
    {
        $asignaciones = DB::table('remision_tecnica_animal')
            ->where('remision_recibe_id', $remisionRecibeId)
            ->where('tecnica_id', $tecnicaId)
            ->join('animales', 'remision_tecnica_animal.animal_id', '=', 'animales.id')
            ->select('remision_tecnica_animal.id as asignacion_id', 'animales.nombre as animal')
            ->get();

        return view('dashboard.registrar_resultado_global', compact('asignaciones'));
    }

    public function guardarResultados(Request $request, $remisionRecibeId, $tecnicaId)
    {
        $resultados = $request->input('resultados', []);
        // formato esperado: [ asignacion_id => "texto resultado", ... ]

        foreach ($resultados as $asignacionId => $valor) {
            DB::table('resultados')->updateOrInsert(
                ['remision_tecnica_animal_id' => $asignacionId],
                [
                    'observaciones' => $valor,
                    'estado' => 'registrado',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return redirect()->route('show.remision ', $remisionRecibeId)
            ->with('success', 'Resultados guardados correctamente.');
    }
}
