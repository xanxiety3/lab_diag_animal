<?php

namespace App\Http\Controllers;

use App\Models\RemisionMuestraEnvio;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

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
            'tiposMuestras'
        ])->findOrFail($id);


        $cliente = $remision->persona;
        $direccion = $cliente->direcciones->first();
        $recibe = $remision->remision_muestra_recibe;
        $responsable = $recibe?->responsable;

        // 📂 Carpeta temporal
        $outputDir = storage_path("app/public/remisiones_{$remision->id}");
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        foreach ($remision->tiposMuestras as $tipo) {
            $tplPath = storage_path('app/plantillas/remision.docx');
            $template = new \PhpOffice\PhpWord\TemplateProcessor($tplPath);

            // Fecha y hora
            $fecha = $remision->fecha;
            $template->setValue('fecha', $fecha?->format('d/m/Y') ?? '');
            $template->setValue('hora', $fecha?->format('H:i') ?? '');

            // Cliente
            $template->setValue('nombre_cliente', trim(($cliente->nombres ?? '') . ' ' . ($cliente->apellidos ?? '')));
            $template->setValue('documento', $cliente->numero_documento ?? '');
            $template->setValue('telefono', $cliente->telefono ?? '');
            $template->setValue('correo', $cliente->correo ?? '');
            $template->setValue('direccion', $direccion?->direccion_detallada ?? '');

            // Muestra
            $template->setValue('tipo_muestra', $tipo->nombre);
            $template->setValue('cantidad_muestra', $tipo->pivot->cantidad_muestra ?? '');
            $refrigeracion = $tipo->pivot->refrigeracion ?? null;
            $template->setValue('refrigeracion_si', $refrigeracion ? 'X' : '');
            $template->setValue('refrigeracion_no', $refrigeracion === 0 ? 'X' : '');

            // Responsable
            $template->setValue('responsable', $responsable->name ?? '');
            $template->setValue('doc_responsable', $responsable->numero_documento ?? '');
            $template->setValue('tel_responsable', $responsable->telefono ?? '');
            $template->setValue('rol', $responsable->rol->nombre ?? '');

            // -----------------------------
            // 🔹 Datos de técnicas (ensayos solicitados)
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
                // Si no hay técnicas, dejamos una fila vacía
                $template->setValue('ensayo', '—');
                $template->setValue('valor_unitario', '');
                $template->setValue('cantidad', '');
                $template->setValue('valor_total', '');
            }


            // Guardar archivo individual
            $fileName = "remision_{$remision->id}_{$tipo->nombre}.docx";
            $path = "$outputDir/$fileName";
            $template->saveAs($path);
        }
      
      

        // ✅ Datos generales del cliente
        $template->setValue('nombre_cliente', trim(($cliente->nombres ?? '') . ' ' . ($cliente->apellidos ?? '')));
        $template->setValue('telefono', $cliente->telefono ?? '');
        $template->setValue('correo', $cliente->correo ?? '');
       $template->setValue('departamento', $direccion?->municipio?->departamento?->nombre ?? '');

        $template->setValue('municipio', $direccion?->municipio?->nombre ?? '');
        $template->setValue('tipo_ubicacion', $direccion?->tipos_ubicacion?->nombre ?? '');
    


        // ✅ Empresa
        $template->setValue('empresa_si', $cliente->empresa ? 'X' : '');
        $template->setValue('empresa_no', $cliente->empresa ? '' : 'X');
        $template->setValue('nombre_empresa', $cliente->nombre_empresa ?? '');

        // ✅ Cliente SENA
        $template->setValue('sena_si', $cliente->rol_sena ? 'X' : '');
        $template->setValue('sena_no', $cliente->rol_sena ? '' : 'X');
        $template->setValue('rol_sena', $cliente->rol_sena ?? '');


        // Datos del remitente
        $template->setValue('responsable', $responsable->name ?? '');
        $template->setValue('fecha_toma', $remision->fecha?->format('d/m/Y') ?? '');
        $template->setValue('hora_toma', $remision->fecha?->format('H:i') ?? '');
        $template->setValue('tel_responsable', $responsable->telefono ?? '');
        $template->setValue('correo', $responsable->email ?? '');

        // -----------------------------
        // 🔹 Detalles de las Muestras a Procesar
        // -----------------------------
        // $detalles = $recibe?->detalles_muestras ?? collect();

        // if ($detalles->isNotEmpty()) {
        //     $template->cloneRow('prueba', $detalles->count());

        //     foreach ($detalles as $i => $detalle) {
        //         $n = $i + 1;

        //         // Formatear especie y sexo para que no aparezca JSON
        //         $especie = $detalle->animal?->especie?->nombre ?? '';
        //         $sexo = $detalle->animal?->sexo?->descripcion ?? '';

        //         $template->setValue("prueba#{$n}", $detalle->prueba ?? '—');
        //         $template->setValue("id_item#{$n}", $detalle->id ?? '');
        //         $template->setValue("edad#{$n}", $detalle->edad ?? '');
        //         $template->setValue("especie#{$n}", $especie);
        //         $template->setValue("sexo#{$n}", $sexo);
        //         $template->setValue("observaciones#{$n}", $detalle->observaciones ?? '');
        //     }
        // } else {
        //     // Si no hay detalles
        //     $template->setValue('prueba', '—');
        //     $template->setValue('id_item', '');
        //     $template->setValue('edad', '');
        //     $template->setValue('especie', '');
        //     $template->setValue('sexo', '');
        //     $template->setValue('observaciones', '');
        // }



        // 📦 Generar ZIP
        $zipPath = storage_path("app/public/remision_{$remision->id}.zip");
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach (glob("$outputDir/*.docx") as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
