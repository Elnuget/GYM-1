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
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cliente\BienvenidaController;
use App\Http\Controllers\Cliente\OnboardingController;
use App\Http\Controllers\Cliente\DashboardController;
use App\Http\Controllers\Cliente\PerfilController;
use App\Http\Controllers\Cliente\RutinaController;
use App\Http\Controllers\Cliente\ReporteController;
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
    Route::resource('duenos-gimnasio', DuenoGimnasioController::class)
        ->middleware(['auth', 'verified']);
    Route::resource('gimnasios', GimnasioController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('pagos-gimnasios', PagoGimnasioController::class);

    // Rutas para roles y usuarios (protegidas con middleware de admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
    });

    Route::get('/completar-registro', [RegisterController::class, 'completarRegistro'])->name('completar.registro');
    Route::post('/completar-registro', [RegisterController::class, 'completarRegistroStore'])->name('completar.registro.store');

    // Grupo de rutas para clientes
    Route::middleware(['auth', 'role:cliente'])
        ->prefix('cliente')
        ->name('cliente.')
        ->group(function () {
            // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');
            
            // Perfil
            Route::prefix('perfil')->group(function () {
                Route::get('/informacion', [PerfilController::class, 'informacion'])->name('perfil.informacion');
                Route::get('/medidas', [PerfilController::class, 'medidas'])->name('perfil.medidas');
                Route::get('/objetivos', [PerfilController::class, 'objetivos'])->name('perfil.objetivos');
                Route::put('/actualizar', [PerfilController::class, 'actualizar'])->name('perfil.actualizar');
                Route::post('/medidas', [PerfilController::class, 'storeMedidas'])->name('perfil.medidas.store');
                Route::post('/objetivos', [PerfilController::class, 'storeObjetivo'])->name('perfil.objetivos.store');
            });

            // Rutinas
            Route::prefix('rutinas')->group(function () {
                Route::get('/actual', [RutinaController::class, 'actual'])->name('rutinas.actual');
                Route::get('/historial', [RutinaController::class, 'historial'])->name('rutinas.historial');
                Route::get('/ejercicios', [RutinaController::class, 'ejercicios'])->name('rutinas.ejercicios');
                Route::get('ejercicios/{id}', [RutinaController::class, 'ejercicioDetalles'])->name('rutinas.ejercicio');
                Route::put('{rutina}/progreso', [RutinaController::class, 'actualizarProgreso'])->name('rutinas.progreso');
                Route::post('{rutina}/solicitar-cambio', [RutinaController::class, 'solicitarCambio'])->name('rutinas.solicitar-cambio');
            });

            // Nutrición
            Route::prefix('nutricion')->group(function () {
                Route::get('/', [App\Http\Controllers\Cliente\NutricionController::class, 'actual'])->name('nutricion');
                Route::get('/historial', [App\Http\Controllers\Cliente\NutricionController::class, 'historial'])->name('nutricion.historial');
                Route::get('/{nutricion}', [App\Http\Controllers\Cliente\NutricionController::class, 'show'])->name('nutricion.show');
                Route::post('/{nutricion}/registrar', [App\Http\Controllers\Cliente\NutricionController::class, 'registrarComida'])->name('nutricion.registrar');
                Route::post('/{nutricion}/solicitar-cambio', [App\Http\Controllers\Cliente\NutricionController::class, 'solicitarCambio'])->name('nutricion.solicitar-cambio');
            });

            // Asistencias
            Route::prefix('asistencias')->group(function () {
                Route::get('/', [App\Http\Controllers\Cliente\AsistenciaController::class, 'index'])
                    ->name('asistencias');
                Route::post('/entrada', [App\Http\Controllers\Cliente\AsistenciaController::class, 'registrarEntrada'])
                    ->name('asistencias.entrada');
                Route::post('/{asistencia}/salida', [App\Http\Controllers\Cliente\AsistenciaController::class, 'registrarSalida'])
                    ->name('asistencias.salida');
            });
            
            // Membresía
            Route::get('/membresia', [App\Http\Controllers\Cliente\MembresiaController::class, 'index'])
                ->name('membresia');

            // Comunicación
            Route::prefix('comunicacion')->group(function () {
                Route::get('/', [App\Http\Controllers\Cliente\ComunicacionController::class, 'index'])
                    ->name('comunicacion');
                Route::post('/mensajes', [App\Http\Controllers\Cliente\ComunicacionController::class, 'enviarMensaje'])
                    ->name('comunicacion.enviar-mensaje');
                Route::post('/notificaciones/{notificacion}/marcar-leida', [App\Http\Controllers\Cliente\ComunicacionController::class, 'marcarNotificacionLeida'])
                    ->name('comunicacion.marcar-notificacion-leida');
                Route::post('/mensajes/{mensaje}/marcar-leido', [App\Http\Controllers\Cliente\ComunicacionController::class, 'marcarMensajeLeido'])
                    ->name('comunicacion.marcar-mensaje-leido');
            });

            // Reportes
            Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes');
            Route::get('/reportes/pdf', [ReporteController::class, 'exportarPDF'])->name('reportes.pdf');
            Route::get('/reportes/excel', [ReporteController::class, 'exportarExcel'])->name('reportes.excel');

            // Pagos en Línea
            Route::prefix('pagos')->group(function () {
                Route::get('/', [App\Http\Controllers\Cliente\PagoController::class, 'index'])->name('pagos.index');
                Route::post('/', [App\Http\Controllers\Cliente\PagoController::class, 'store'])->name('pagos.store');
                Route::get('/{pago}', [App\Http\Controllers\Cliente\PagoController::class, 'show'])->name('pagos.show');
            });
        });

    // Rutas de onboarding
    Route::middleware(['auth', 'role:cliente'])
        ->prefix('onboarding')
        ->name('onboarding.')
        ->group(function () {
            Route::get('/perfil', [OnboardingController::class, 'perfil'])->name('perfil');
            Route::post('/perfil', [OnboardingController::class, 'storePerfil'])->name('perfil.store');
            Route::get('/medidas', [OnboardingController::class, 'medidas'])->name('medidas');
            Route::post('/medidas', [OnboardingController::class, 'storeMedidas'])->name('medidas.store');
            Route::get('/objetivos', [OnboardingController::class, 'objetivos'])->name('objetivos');
            Route::post('/objetivos', [OnboardingController::class, 'storeObjetivos'])->name('objetivos.store');
            Route::get('/tour', [OnboardingController::class, 'tour'])->name('tour');
            Route::post('/tour/complete', [OnboardingController::class, 'completeTour'])->name('tour.complete');
        });

    Route::get('/cliente/perfil/objetivos', [PerfilController::class, 'objetivos'])
        ->name('cliente.perfil.objetivos');
});

// Rutas de registro personalizadas
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register/cliente', [RegisterController::class, 'registerCliente'])->name('register.cliente');
    Route::post('/register/dueno', [RegisterController::class, 'registerDueno'])->name('register.dueno');
    Route::post('/register/gimnasio', [RegisterController::class, 'registerGimnasio'])->name('register.gimnasio');
    Route::post('/register/empleado', [RegisterController::class, 'registerEmpleado'])->name('register.empleado');
});

require __DIR__.'/auth.php';
