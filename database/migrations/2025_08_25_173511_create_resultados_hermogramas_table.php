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
        Schema::create('resultados_hermogramas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resultado_id')->constrained('resultados')->onDelete('cascade');
            $table->string('codigo_interno')->nullable();
            $table->string('especie')->nullable();
            $table->string('sexo')->nullable();
            $table->decimal('hb', 5, 2)->nullable();
            $table->decimal('hto', 5, 2)->nullable();
            $table->integer('leucocitos')->nullable();
            $table->integer('neu')->nullable();
            $table->integer('eos')->nullable();
            $table->integer('bas')->nullable();
            $table->integer('lin')->nullable();
            $table->integer('mon')->nullable();
            $table->integer('plaquetas')->nullable();
            $table->decimal('vcm', 5, 2)->nullable();
            $table->decimal('hcm', 5, 2)->nullable();
            $table->decimal('chcm', 5, 2)->nullable();
            $table->string('hemoparasitos')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_hermogramas');
    }
};
