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
        Schema::create('animal_tecnica_resultado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remision_muestra_recibe_id')
              ->constrained('remision_muestra_recibe')
              ->onDelete('cascade');

        $table->foreignId('tecnica_id')
              ->constrained('tecnicas_muestra')
              ->onDelete('cascade');

        $table->foreignId('animal_id')
              ->constrained('animales')
              ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_tecnica_resultado');
    }
};
