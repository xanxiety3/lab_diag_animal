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
        Schema::create('remision_tecnica_animal', function (Blueprint $table) {
            $table->id();

            // Relaciona la remisión recibida
            $table->foreignId('remision_recibe_id')
                ->constrained('remision_muestra_recibe')
                ->onDelete('cascade');

            // Relaciona la técnica
            $table->foreignId('tecnica_id')
                ->constrained('tecnicas_muestra')
                ->onDelete('cascade');

            // Relaciona el animal
            $table->foreignId('animal_id')
                ->constrained('animales')
                ->onDelete('cascade');

            $table->timestamps();

            // Evita duplicados del mismo animal + técnica + remisión
            $table->unique(['remision_recibe_id', 'tecnica_id', 'animal_id'], 'unique_remision_tecnica_animal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remision_tecnica_animal');
    }
};
