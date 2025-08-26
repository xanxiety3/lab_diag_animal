<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resultados', function (Blueprint $table) {
            // Eliminar la columna antigua si existe
            if (Schema::hasColumn('resultados', 'remision_muestra_recibe_id')) {
                $table->dropForeign(['remision_muestra_recibe_id']);
                $table->dropColumn('remision_muestra_recibe_id');
            }

            // Agregar la nueva columna
            $table->foreignId('muestra_recibe_tecnica_id')
                  ->constrained('muestra_recibe_tecnica')
                  ->onDelete('cascade')
                  ->after('id'); // opcional, para dejarla al inicio
        });
    }

    public function down(): void
    {
        Schema::table('resultados', function (Blueprint $table) {
            $table->foreignId('remision_muestra_recibe_id')
                  ->constrained('remision_muestra_recibe')
                  ->onDelete('cascade')
                  ->after('id');

            $table->dropForeign(['muestra_recibe_tecnica_id']);
            $table->dropColumn('muestra_recibe_tecnica_id');
        });
    }
};


