<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetLabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔴 Desactivar claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 🔄 Truncar las tablas en orden (pivot primero)
        DB::table('animal_tecnica_resultado')->truncate();
        DB::table('remision_tipo_muestra')->truncate();
        DB::table('remision_muestra_recibe')->truncate();
        DB::table('remision_muestra_envio')->truncate();
        DB::table('animales')->truncate();
        DB::table('personas')->truncate();
        DB::table('direcciones')->truncate();
        DB::table('muestra_recibe_tecnica')->truncate();
        DB::table('remision_recibe_animales')->truncate();
        DB::table('resultados_copro_frescos')->truncate();





        // 🟢 Activar claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
