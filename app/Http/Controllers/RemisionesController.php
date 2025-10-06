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

        $remision = RemisionMuestraEnvio::create([
            'fecha' => now(),
            'cliente_id' => $request->cliente_id,
            'observaciones' => $request->observaciones,
        ]);

        foreach ($request->tipos_muestra as $tipoId => $datos) {
            if (isset($datos['activo']) && $datos['activo'] == 1) {
                $remision->tiposMuestras()->attach($tipoId, [
                    'cantidad_muestra' => $datos['cantidad'] ?? 0,
                    'refrigeracion' => $datos['refrigeracion'] ?? 0,
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
        'animales' => 'required|array',
        'animales.*' => 'exists:animales,id',
    ]);

    $muestraRecibe = RemisionMuestraRecibe::create([
        'muestra_enviada_id' => $request->muestra_enviada_id,
        'responsable_id' => auth()->id(),
        'fecha' => now(),
    ]);

    // Construir el array para attach solo con las seleccionadas
    $tecnicasAttach = [];
    foreach ($request->tecnicas as $tecnica) {
        if (!empty($tecnica['id']) && intval($tecnica['cantidad']) > 0) {
            $tecnicasAttach[$tecnica['id']] = [
                'cantidad' => intval($tecnica['cantidad']),
            ];
        }
    }

    // Guardar técnicas seleccionadas
    if (!empty($tecnicasAttach)) {
        $muestraRecibe->tecnicas()->attach($tecnicasAttach);
    }

    // Guardar animales seleccionados
    $muestraRecibe->animales()->attach($request->animales);

    return redirect()->route('dashboard')
        ->with('success', 'Recepción de muestra registrada correctamente.');
}

}
