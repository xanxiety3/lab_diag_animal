<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('resultados', function (Blueprint $table) {
            // Relación a la tabla pivot
            $table->foreignId('remision_tecnica_animal_id')
                ->after('id')
                ->constrained('remision_tecnica_animal')
                ->onDelete('cascade');

            // Opcionales, si no los tienes ya
            $table->text('observaciones')->nullable();
  
  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resultados', function (Blueprint $table) {
            //
        });
    }
};
