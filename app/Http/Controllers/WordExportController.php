<?php

namespace App\Http\Controllers;

use App\Models\RemisionMuestraEnvio;

use Illuminate\Support\Str;

class WordExportController extends Controller
{


    //  USTEDES TIENEN QUE HACER UNA VERIFIACION DE CONDICIONES AMBIENTALES, HUMEDAD, TEMPERATURA Y SUPERFICIES

    // REPORTES EN EXCEL DE POBLACION ATENDIDA, POR MES, POR NUMERO DE ENSAYO, POR AÑO

    public function exportarRemision($id)
    {
        $remision = RemisionMuestraEnvio::with([
            'persona.direcciones.municipio.departamento',
            'persona.direcciones.tipos_ubicacion',
            'remision_muestra_recibe.responsable',
            'remision_muestra_recibe.tecnicas',
            'remision_muestra_recibe.animalTecnicaResultados.animal',
            'remision_muestra_recibe.animalTecnicaResultados.tecnica',
            'tiposMuestras'
        ])->findOrFail($id);

        $cliente = $remision->persona;
        $direccion = $cliente->direcciones->first();
        $recibe = $remision->remision_muestra_recibe;
        $responsable = $recibe?->responsable;

        $outputDir = storage_path("app/public/remisiones_{$remision->id}");
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        foreach ($remision->tiposMuestras as $tipo) {
            $tplPath = storage_path('app/plantillas/remision.docx');
            $template = new \PhpOffice\PhpWord\TemplateProcessor($tplPath);

            // 📅 Fecha y hora envio
            $fecha = $remision->fecha;
            $template->setValue('fecha', $fecha?->format('d/m/Y') ?? '');
            $template->setValue('hora', $fecha?->format('H:i') ?? '');

            // fecha y horaRecibida
            $fechaRecibida = $recibe->fecha;
            $template->setValue('fecha_toma', $fechaRecibida?->format('d/m/Y') ?? '');
            $template->setValue('hora_toma', $fechaRecibida?->format('H:i') ?? '');

            // 👤 Cliente
            $template->setValue('nombre_cliente', trim(($cliente->nombres ?? '') . ' ' . ($cliente->apellidos ?? '')));
            $template->setValue('documento', $cliente->numero_documento ?? '');
            $template->setValue('telefono', $cliente->telefono ?? '');
            $template->setValue('correo', $cliente->correo ?? '');
            $template->setValue('direccion', $direccion?->direccion_detallada ?? '');

            // 🌎 Ubicación
            $template->setValue('departamento', $direccion?->municipio?->departamento?->nombre ?? '');
            $template->setValue('municipio', $direccion?->municipio?->nombre ?? '');
            $template->setValue('tipo_ubicacion', $direccion?->tipos_ubicacion?->nombre ?? '');

            // 🏢 Empresa
            $template->setValue('empresa_si', $cliente->empresa ? 'X' : '');
            $template->setValue('empresa_no', $cliente->empresa ? '' : 'X');
            $template->setValue('nombre_empresa', $cliente->nombre_empresa ?? '');

            // 🎓 Cliente SENA
            $template->setValue('sena_si', $cliente->rol_sena ? 'X' : '');
            $template->setValue('sena_no', $cliente->rol_sena ? '' : 'X');
            $template->setValue('rol_sena', $cliente->rol_sena ?? '');

            // 🧪 Tipo de muestra
            $template->setValue('tipo_muestra', $tipo->nombre);
            $template->setValue('cantidad_muestra', $tipo->pivot->cantidad_muestra ?? '');
            $refrigeracion = $tipo->pivot->refrigeracion ?? null;
            $template->setValue('refrigeracion_si', $refrigeracion ? 'X' : '');
            $template->setValue('refrigeracion_no', $refrigeracion === 0 ? 'X' : '');

            // 👨‍🔬 Responsable
            $template->setValue('responsable', $responsable->name ?? '');
            $template->setValue('doc_responsable', $responsable->numero_documento ?? '');
            $template->setValue('tel_responsable', $responsable->telefono ?? '');
            $template->setValue('email', $responsable->email ?? '');
            $template->setValue('rol', $responsable->rol->nombre ?? '');

            // -----------------------------
            // 🔹 Técnicas (ensayos)
            // -----------------------------
            $tecnicas = $recibe?->tecnicas ?? collect();

            if ($tecnicas->isNotEmpty()) {
                $template->cloneRow('ensayo', $tecnicas->count());
                foreach ($tecnicas as $i => $tec) {
                    $n = $i + 1;
                    $valorUnitario = $tec->valor_unitario ?? 0;
                    $cantidad = $tec->pivot->cantidad ?? 0;
                    $valorTotal = $valorUnitario * $cantidad;

                    $template->setValue("ensayo#{$n}", $tec->nombre);
                    $template->setValue("valor_unitario#{$n}", number_format($valorUnitario, 0, ',', '.'));
                    $template->setValue("cantidad#{$n}", $cantidad);
                    $template->setValue("valor_total#{$n}", number_format($valorTotal, 0, ',', '.'));
                }
            } else {
                $template->setValue('ensayo', '—');
                $template->setValue('valor_unitario', '');
                $template->setValue('cantidad', '');
                $template->setValue('valor_total', '');
            }


            // TIPOS DE MUESTRA

            // Normalizamos los nombres
            $tipos = collect($remision->tiposMuestras)
                ->pluck('nombre')
                ->map(fn($n) => Str::of($n)->lower()->squish())
                ->toArray();

            // Como en tu BD están "hematológica" y "coprológica"
            $template->setValue('hematologia_x', in_array('hematológica', $tipos) ? 'X' : '');
            $template->setValue('parasitologia_x', in_array('coprológica', $tipos) ? 'X' : '');




            // DETALLES DE ANIMALES ASOCIADOS A LA TÉCNICA
            $detalles = $recibe?->animalTecnicaResultados?->map(function ($atr) {
                return [
                    'tecnica' => $atr->tecnica?->siglas ?? '',
                    'id_items' => $atr->animal?->id ?? '',
                    'edad_meses' => $atr->animal?->edad ?? '',
                    'especie_raza' => trim(($atr->animal?->especie?->nombre ?? '') . ' / ' . ($atr->animal?->raza?->nombre ?? '')),
                    'sexo' => $atr->animal?->sexo?->descripcion ?? '',
                    'observaciones' => $atr->animal?->observaciones ?? '',
                ];
            });
            if ($detalles && $detalles->isNotEmpty()) {
                $template->cloneRow('tecnica', $detalles->count());

                foreach ($detalles as $i => $d) {
                    $n = $i + 1;
                    $template->setValue("tecnica#{$n}", $d['tecnica']);
                    $template->setValue("id_items#{$n}", $d['id_items']);
                    $template->setValue("edad_meses#{$n}", $d['edad_meses']);
                    $template->setValue("especie_raza#{$n}", $d['especie_raza']);
                    $template->setValue("sexo#{$n}", $d['sexo']);
                    $template->setValue("observaciones#{$n}", $d['observaciones']);
                }
            } else {
                // Si no hay datos, dejamos una fila vacía
                $template->setValue('tecnica', '—');
                $template->setValue('id_items', '');
                $template->setValue('edad_meses', '');
                $template->setValue('especie_raza', '');
                $template->setValue('sexo', '');
                $template->setValue('observaciones', '');
            }


            // 💾 Guardar archivo individual
            $fileName = "remision_{$remision->id}_{$tipo->nombre}.docx";
            $path = "$outputDir/$fileName";
            $template->saveAs($path);
        }

        // 📦 Generar ZIP con nombre dinámico
        $safeName = Str::slug(trim(($cliente->nombres ?? '') . ' ' . ($cliente->apellidos ?? '')) ?: "remision_{$remision->id}");
        $zipPath = storage_path("app/public/{$safeName}_{$remision->id}_" . now()->format('Ymd_His') . ".zip");

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach (glob("$outputDir/*.docx") as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        // 🧹 Limpiar archivos temporales
        foreach (glob("$outputDir/*") as $file) {
            @unlink($file);
        }
        @rmdir($outputDir);

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
