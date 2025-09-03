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
        Schema::create('remision_tipo_muestra', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor('remision_id')
                ->constrained('remision_muestra_envio')
                ->onDelete('cascade');

            $table->foreignId('tipo_muestra_id')
                ->constrained('tipos_muestra')
                ->onDelete('cascade');

            $table->integer('cantidad_muestra');
            $table->boolean('refrigeracion')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remision_tipo_muestra');
    }
};
