<?php

use Illuminate\Support\Facades\Route;
// web.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\RemisionesController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use App\Models\Raza;

// PUBLIC ROUTES 
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// PRIVATE ROUTES 
Route::middleware(['auth', IsUserAuth::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/welcome', function () {
        return view('welcome');
    })->name('welcome');

    /// Ruta única para mostrar el wizard (con paso controlado por query ?step=)
    Route::get('/registro', [RegistroController::class, 'showWizard'])->name('registro.wizard');

    // Rutas POST para guardar cada paso
    Route::post('/registro/persona', [RegistroController::class, 'guardarPersona'])->name('registro.persona.guardar');
    Route::post('/registro/animales', [RegistroController::class, 'guardarAnimales'])->name('registro.animales.guardar');
    Route::post('/registro/direccion', [RegistroController::class, 'guardarDireccion'])->name('registro.direccion.guardar');

    Route::get('/razas/{especieId}', [RegistroController::class, 'getByEspecie']);
    Route::get('/municipios/{departamento}', [RegistroController::class, 'municipiosPorDepartamento']);


    Route::get('/remisiones', [RemisionesController::class, 'showForm'])->name('remision.formulario');
    Route::post('/remisiones/enviada', [RemisionesController::class, 'store'])->name('remisiones.store');

    Route::get('/remisiones/recibida', [RemisionesController::class, 'showFormRecibido'])->name('formulario.recibida');
    Route::post('/remisiones/recibida', [RemisionesController::class, 'storeRecibido'])->name('remisiones.recibida');
});

Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin', function () {
        return view('admin.welcome');
    })->name('admin.welcome');
});
