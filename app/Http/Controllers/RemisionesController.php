<?php

namespace App\Http\Controllers;

use App\Models\MuestraRecibeTecnica;
use App\Models\Persona;
use App\Models\RemisionMuestraEnvio;
use App\Models\RemisionMuestraRecibe;
use App\Models\TecnicasMuestra;
use App\Models\TiposMuestra;
use Illuminate\Http\Request;

class RemisionesController extends Controller
{

    public function show($id){
           $remision = RemisionMuestraEnvio::with([
        'persona',
    
        'tiposMuestra'
    ])->findOrFail($id);

    return view('dashboard.show', compact('remision'));
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
                $remision->tiposMuestra()->attach($tipoId, [
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
