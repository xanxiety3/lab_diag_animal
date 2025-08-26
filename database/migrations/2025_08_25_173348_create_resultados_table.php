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
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remision_muestra_recibe_id')
                ->constrained('remision_muestra_recibe')
                ->onDelete('cascade');

            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tecnica_muestra_id')->constrained('tecnicas_muestra')->onDelete('restrict');
            $table->enum('estado', ['borrador', 'finalizado'])->default('borrador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados');
    }
};
