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
        Schema::create('resultado_mcmasters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resultado_id')->constrained('resultados')->onDelete('cascade');
            $table->string('codigo_interno')->nullable();
            $table->decimal('cantidad_muestra', 8, 2)->nullable();
            $table->decimal('solucion_flotacion', 8, 2)->nullable();
            $table->integer('strongylida_c1')->nullable();
            $table->integer('strongylida_c2')->nullable();
            $table->integer('strongylus_c1')->nullable();
            $table->integer('strongylus_c2')->nullable();
            $table->integer('moniezia_c1')->nullable();
            $table->integer('moniezia_c2')->nullable();
            $table->integer('eimeria_c1')->nullable();
            $table->integer('eimeria_c2')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_macmasters');
    }
};
