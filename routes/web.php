<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\RemisionesController;
use App\Http\Controllers\ResultadoController;
use App\Http\Controllers\WordExportController;
use App\Http\Middleware\IsUserAuth;

// -------------------------------------------------------------------------
// RUTAS PÚBLICAS (no requieren autenticación)
// -------------------------------------------------------------------------
Route::get('/', function () {
    return redirect('/login'); // Redirige al login por defecto
});

// Login y registro de usuarios
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Mostrar formulario de login
Route::post('/login', [AuthController::class, 'login']);                      // Procesar login
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register'); // Formulario de registro
Route::post('/register', [AuthController::class, 'register']);                         // Procesar registro



// -------------------------------------------------------------------------
// RUTAS PRIVADAS (solo usuarios autenticados)
// -------------------------------------------------------------------------
Route::middleware(['auth', IsUserAuth::class])->group(function () {
    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | RUTAS COMPARTIDAS (Administrador y Veterinario)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Administrador,Veterinario')->group(function () {

        // -------------------------
        // 📌 REMISIONES
        // -------------------------
        Route::get('/remisiones', [RemisionesController::class, 'showForm'])->name('remision.formulario'); // Formulario de nueva remisión enviada
        Route::post('/remisiones/enviada', [RemisionesController::class, 'store'])->name('remisiones.store'); // Guardar remisión enviada

        Route::get('/remisiones/recibida', [RemisionesController::class, 'showFormRecibido'])->name('formulario.recibida'); // Formulario de remisión recibida
        Route::post('/remisiones/recibida', [RemisionesController::class, 'storeRecibido'])->name('remisiones.recibida');  // Guardar remisión recibida

        Route::get('/ver/{id}', [RemisionesController::class, 'show'])->name('show.remision'); // Ver detalle de una remisión específica


        // -------------------------
        // 📌 DASHBOARD Y REGISTRO (wizard en pasos)
        
        // -------------------------

        Route::get('/export-word/{id}', [WordExportController::class, 'exportarRemision'])->name('export.remision.word'); // Exportar remisión a Word


        Route::get('/registro', [RegistroController::class, 'showWizard'])->name('registro.wizard'); // Mostrar wizard con pasos dinámicos (?step=)
        Route::get('/inicio', [RegistroController::class, 'index'])->name('dashboard');             // Vista principal del dashboard

        // Guardar datos en cada paso del wizard
        Route::post('/registro/persona', [RegistroController::class, 'guardarPersona'])->name('registro.persona.guardar');
        Route::post('/registro/animales', [RegistroController::class, 'guardarAnimales'])->name('registro.animales.guardar');
        Route::post('/registro/direccion', [RegistroController::class, 'guardarDireccion'])->name('registro.direccion.guardar');

        // Datos dinámicos para selects dependientes
        Route::get('/razas/{especieId}', [RegistroController::class, 'getByEspecie']);                  // Obtener razas según especie
        Route::get('/municipios/{departamento}', [RegistroController::class, 'municipiosPorDepartamento']); // Obtener municipios según departamento


        // -------------------------
        // 📌 RESULTADOS


        // -------------------------

        //dashboard de resultados 
        Route::get('/dashboard/resultados', [ResultadoController::class, 'dashboardResultados'])
            ->name('resultados.vista');






        //registrar animales a una tecnica
        Route::get(
            '/resultados/{remision}/{tecnica}/asignar-animales',
            [ResultadoController::class, 'asignarAnimales']
        )->name('resultados.asignar_animales');

        //guardar animales asignados a una tecnica
        Route::post(
            '/tecnicas/{tecnica}/remisiones/{remisionRecibe}/animales',
            [ResultadoController::class, 'guardarAnimales']
        )->name('resultados.guardar_animales');



        Route::get('/remisiones/{remisionRecibe}/elegir-muestra', [ResultadoController::class, 'elegirMuestra'])
            ->name('resultados.elegirMuestra');


        //vista para registrar resultado por tecnica
        Route::get('remisiones/{remisionEnvioId}/tecnicas', [ResultadoController::class, 'elegirTecnica'])
            ->name('resultados.elegir_tecnica');


        // para la vista de resultados segun tecnica
        Route::get('resultados/{remisionRecibe}/{tecnica}/create', [
            ResultadoController::class,
            'createResultado'
        ])->name('resultados.create');
    });

    Route::post(
        'resultados/guardar-multiple/{remisionRecibe}/{tecnica}',
        [ResultadoController::class, 'storeResultadoMultiple']
    )
        ->name('resultados.store_resultado_multiple');
});
