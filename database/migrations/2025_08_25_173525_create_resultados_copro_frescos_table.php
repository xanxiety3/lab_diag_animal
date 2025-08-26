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
        Schema::create('resultados_copro_frescos', function (Blueprint $table) {
             $table->id();
    $table->foreignId('resultado_id')->constrained('resultados')->onDelete('cascade');
    $table->string('codigo_interno')->nullable();
    $table->string('sexo')->nullable();
    $table->string('especie')->nullable();
    // análisis macroscópico
    $table->string('color')->nullable();
    $table->string('consistencia')->nullable();
    $table->boolean('moco')->nullable();
    $table->boolean('sangre')->nullable();
    $table->boolean('celulas_epiteliales')->nullable();
    $table->boolean('celulas_vegetales')->nullable();
    // análisis microscópico
    $table->boolean('huevos')->nullable();
    $table->boolean('quistes')->nullable();
    $table->boolean('levaduras')->nullable();
    $table->boolean('otros')->nullable();
    $table->text('observaciones')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_copro_frescos');
    }
};
