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
        Schema::create('resultados_bearmen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resultado_id')->constrained('resultados')->onDelete('cascade');
            $table->string('codigo_interno')->nullable();
            $table->string('codigo_solicitud')->nullable();
            $table->date('fecha_analisis')->nullable();
            $table->decimal('cantidad_muestra', 8, 2)->nullable();
            $table->enum('larvas', ['ausencia', 'presencia'])->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_bearmen');
    }
};
