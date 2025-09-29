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
        Schema::create('remision_recibe_animales', function (Blueprint $table) {
            $table->id();
              $table->foreignId('remision_muestra_recibe_id')
          ->constrained('remision_muestra_recibe');
    $table->foreignId('animal_id')
          ->constrained('animales');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remision_recibe_animales');
    }
};
