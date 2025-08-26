<?php

namespace App\Http\Controllers;

use App\Models\MuestraRecibeTecnica;
use App\Models\RemisionMuestraRecibe;
use App\Models\TecnicasMuestra;
use Illuminate\Http\Request;

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

        $tecnica = MuestraRecibeTecnica::findOrFail($tecnicaId);

        // Obtenemos el dueño real de esta remisión
        $persona = $remisionRecibe->remision_muestra_envio->persona;

        // Animales de ese dueño
        $animales = $persona ? $persona->animales : collect();

        return view('dashboard.asignar_animales', compact('remisionRecibe', 'tecnica', 'animales', 'persona'));
    }
}
