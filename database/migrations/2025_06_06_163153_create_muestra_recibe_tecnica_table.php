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
        Schema::create('muestra_recibe_tecnica', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('muestra_recibe_id');
            $table->unsignedInteger('tecnica_id');


            $table->foreign('muestra_recibe_id')
                ->references('id')
                ->on('remision_muestra_recibe')
                ->onDelete('cascade');

            $table->foreign('tecnica_id')
                ->references('id')
                ->on('tecnicas_muestra')
                ->onDelete('cascade');
           
          

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muestra_recibe_tecnica');
    }
};
