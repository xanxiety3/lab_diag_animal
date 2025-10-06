<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TiposDocumento;
use App\Models\Persona;
use App\Models\Especy;
use App\Models\Raza;
use App\Models\Sexo;
use App\Models\Animale;
use App\Models\Departamento;
use App\Models\Direccion;
use App\Models\Direccione;
use App\Models\Municipio;
use App\Models\RemisionMuestraEnvio;
use App\Models\RemisionMuestraRecibe;
use App\Models\TiposDireccion;
use App\Models\TiposUbicacion;

class RegistroController extends Controller
{
    public function index(Request $request)
    {
        $remisiones = RemisionMuestraRecibe::with(['persona', 'remision_muestra_envio.tiposMuestras'])
            ->resultado($request->filtro_resultado)
            ->estado($request->filtro_estado)
            ->orderBy('fecha', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.index', compact('remisiones'));
    }



    public function showWizard(Request $request)
    {
        $step = $request->query('step', 'persona'); // paso por defecto

        $data = ['step' => $step];

        switch ($step) {
            case 'persona':
                $data['tipoDoc'] = TiposDocumento::all();
                break;

            case 'animales':
                if (!session('persona_id')) {
                    return redirect()->route('registro.wizard', ['step' => 'persona']);
                }
                $data['especies'] = Especy::all();
                $data['razas'] = Raza::all();
                $data['sexos'] = Sexo::all();
                break;

            case 'direccion':
                if (!session('persona_id')) {
                    return redirect()->route('registro.wizard', ['step' => 'persona']);
                }
                $data['departamentos'] = Departamento::all();
                $data['municipios'] = Municipio::all();
                $data['tiposDireccion'] = TiposDireccion::all();
                $data['tiposUbicacion'] = TiposUbicacion::all();

                break;

            case 'completado':
                break;

            default:
                return redirect()->route('registro.wizard', ['step' => 'persona']);
        }

        return view('registro.wizard', $data);
    }

    public function guardarPersona(Request $request)
    {
        $validated = $request->validate([
            'tipo_documento_id' => 'required|integer',
            'numero_documento' => 'required|unique:personas,numero_documento',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'correo' => 'required|email',
            'telefono' => 'required|string',

            // Nuevos campos
            'es_empresa' => 'nullable|boolean',
            'nombre_empresa' => 'required_if:es_empresa,1|string|max:255|nullable',
            'es_sena' => 'nullable|boolean',
            'rol_sena' => 'required_if:es_sena,1|string|max:255|nullable',
        ]);

        // Normalizamos los checkboxes a 0/1
        $validated['es_empresa'] = $request->has('es_empresa') ? 1 : 0;
        $validated['es_sena'] = $request->has('es_sena') ? 1 : 0;

        $persona = Persona::create($validated);

        session(['persona_id' => $persona->id]);

        return redirect()->route('registro.wizard', ['step' => 'animales']);
    }


    public function guardarAnimales(Request $request)
    {
        $personaId = session('persona_id');
        if (!$personaId) {
            return redirect()->route('registro.wizard', ['step' => 'persona']);
        }

        $validated = $request->validate([
            'animales' => 'required|array|min:1',
            'animales.*.nombre' => 'required|string|max:255',
            'animales.*.especie_id' => 'required|integer',
            'animales.*.raza_id' => 'required|integer',
            'animales.*.sexo_id' => 'required|integer',
            'animales.*.edad' => 'required|integer|min:0',
        ]);

        foreach ($validated['animales'] as $animalData) {
            Animale::create([
                'duenio_id' => $personaId,
                'nombre' => $animalData['nombre'],
                'especie_id' => $animalData['especie_id'],
                'raza_id' => $animalData['raza_id'],
                'sexo_id' => $animalData['sexo_id'],
                'edad' => $animalData['edad'],
            ]);
        }

        return redirect()->route('registro.wizard', ['step' => 'direccion']);
    }

    public function getByEspecie($especieId)
    {
        $razas = Raza::where('especie_id', $especieId)->get(['id', 'nombre']);
        return response()->json($razas);
    }



    public function guardarDireccion(Request $request)
    {
        $personaId = session('persona_id');
        if (!$personaId) {
            return redirect()->route('registro.wizard', ['step' => 'persona']);
        }

        $validated = $request->validate([
            'municipio_id' => 'required|integer',
            'tipo_direccion_id' => 'required|integer',
            'tipo_ubicacion_id' => 'required|integer',
            'direccion_detallada' => 'required|string',
        ]);

        Direccione::create([
            'cliente_id' => $personaId,
            'municipio_id' => $validated['municipio_id'],
            'tipo_direccion_id' => $validated['tipo_direccion_id'],
            'tipo_ubicacion_id' => $validated['tipo_ubicacion_id'],
            'direccion_detallada' => $validated['direccion_detallada'],
        ]);

        // Limpieza opcional
        session()->forget('persona_id');

        return redirect()
            ->route('remision.formulario', ['cliente_id' => $personaId])
            ->with('success', 'Cliente creado y seleccionado automáticamente.');
    }


    public function municipiosPorDepartamento($departamentoId)
    {
        $municipios = Municipio::where('departamento_id', $departamentoId)->get(['id', 'nombre']);
        return response()->json($municipios);
    }
}
