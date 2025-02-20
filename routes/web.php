<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembresiaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\MetodoPagoController;
use App\Http\Controllers\RutinaPredefinidaController;
use App\Http\Controllers\AsignacionRutinaController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\NutricionController;
use App\Http\Controllers\ImplementoController;
use App\Http\Controllers\DuenoGimnasioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GimnasioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PagoGimnasioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('membresias', MembresiaController::class);
    Route::post('membresias/{membresia}/registrar-visita', [MembresiaController::class, 'registrarVisita'])
        ->name('membresias.registrar-visita');
    Route::resource('pagos', PagoController::class);
    Route::resource('metodos-pago', MetodoPagoController::class, [
        'parameters' => ['metodos-pago' => 'metodoPago']
    ]);
    Route::get('rutinas-predefinidas', [RutinaPredefinidaController::class, 'index'])
        ->name('rutinas-predefinidas.index');
    Route::get('rutinas-predefinidas/create', [RutinaPredefinidaController::class, 'create'])
        ->name('rutinas-predefinidas.create');
    Route::post('rutinas-predefinidas', [RutinaPredefinidaController::class, 'store'])
        ->name('rutinas-predefinidas.store');
    Route::get('rutinas-predefinidas/{rutinaPredefinida}/edit', [RutinaPredefinidaController::class, 'edit'])
        ->name('rutinas-predefinidas.edit');
    Route::put('rutinas-predefinidas/{rutinaPredefinida}', [RutinaPredefinidaController::class, 'update'])
        ->name('rutinas-predefinidas.update');
    Route::delete('rutinas-predefinidas/{rutinaPredefinida}', [RutinaPredefinidaController::class, 'destroy'])
        ->name('rutinas-predefinidas.destroy');
    Route::resource('asignacion-rutinas', AsignacionRutinaController::class);
    Route::resource('asistencias', AsistenciaController::class);
    Route::post('asistencias/entrada', [AsistenciaController::class, 'registrarEntrada'])
        ->name('asistencias.entrada');
    Route::post('asistencias/{asistencia}/salida', [AsistenciaController::class, 'registrarSalida'])
        ->name('asistencias.salida');
    Route::resource('nutricion', NutricionController::class);
    Route::resource('implementos', ImplementoController::class);
    Route::resource('duenos-gimnasio', DuenoGimnasioController::class);
    Route::resource('gimnasios', GimnasioController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('pagos-gimnasios', PagoGimnasioController::class);

    // Rutas para roles y usuarios (protegidas con middleware de admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';
