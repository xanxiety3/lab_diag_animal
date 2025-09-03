<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\RemisionesController;
use App\Http\Controllers\ResultadoController;
use App\Http\Middleware\IsUserAuth;

// PUBLIC ROUTES 
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// PRIVATE ROUTES (solo usuarios autenticados)
Route::middleware(['auth', IsUserAuth::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | RUTAS COMPARTIDAS (ADMIN y VETERINARIO)
    |--------------------------------------------------------------------------
    | Ejemplo: tanto admin como veterinario pueden manejar remisiones.
    */
    Route::middleware('role:Administrador,Veterinario')->group(function () {
        Route::get('/remisiones', [RemisionesController::class, 'showForm'])->name('remision.formulario');
        Route::post('/remisiones/enviada', [RemisionesController::class, 'store'])->name('remisiones.store');

        Route::get('/remisiones/recibida', [RemisionesController::class, 'showFormRecibido'])->name('formulario.recibida');
        Route::post('/remisiones/recibida', [RemisionesController::class, 'storeRecibido'])->name('remisiones.recibida');
        /// Ruta única para mostrar el wizard (con paso controlado por query ?step=)
        Route::get('/registro', [RegistroController::class, 'showWizard'])->name('registro.wizard');
        Route::get('/inicio', [RegistroController::class, 'index'])->name('dashboard');
        Route::get('/ver/{id}', [RemisionesController::class, 'show'])->name('show.remision');


        // 1️⃣ Guardar animales seleccionados para una técnica
        Route::post(
            '/tecnicas/{tecnica}/remisiones/{remisionRecibe}/animales',
            [ResultadoController::class, 'guardarAnimales']
        )->name('resultados.guardarAnimales');

        // 2️⃣ Mostrar la vista de registrar resultados para una técnica y remisión
        Route::get(
            '/tecnicas/{tecnica}/remisiones/{remisionRecibe}/resultados',
            [ResultadoController::class, 'resultadosIndex']
        )->name('tecnicas.resultados.index');

        // 3️⃣ Guardar los resultados ingresados
        Route::post(
            '/tecnicas/{tecnica}/remisiones/{remisionRecibe}/resultados',
            [ResultadoController::class, 'guardarResultados']
        )->name('resultados.guardar');


        Route::get('/resultados/{remision}/create', [ResultadoController::class, 'create'])->name('resultados.create');


        Route::get('/resultados/asignar-animales/{remision}/{tecnica}', [ResultadoController::class, 'asignarAnimales'])->name('resultados.asignar_animales');



        // Rutas POST para guardar cada paso
        Route::post('/registro/persona', [RegistroController::class, 'guardarPersona'])->name('registro.persona.guardar');
        Route::post('/registro/animales', [RegistroController::class, 'guardarAnimales'])->name('registro.animales.guardar');
        Route::post('/registro/direccion', [RegistroController::class, 'guardarDireccion'])->name('registro.direccion.guardar');

        // Datos dinámicos
        Route::get('/razas/{especieId}', [RegistroController::class, 'getByEspecie']);
        Route::get('/municipios/{departamento}', [RegistroController::class, 'municipiosPorDepartamento']);
    });
});
