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
        Schema::table('remision_muestra_recibe', function (Blueprint $table) {
            $table->unsignedBigInteger('animal_id')->nullable()->after('muestra_enviada_id');
            $table->foreign('animal_id')->references('id')->on('animales')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('remision_muestra_recibe', function (Blueprint $table) {
            //
        });
    }
};
