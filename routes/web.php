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
use App\Http\Controllers\TipoMembresiaController;
use App\Http\Controllers\Cliente\AsistenciaController as ClienteAsistenciaController;
use App\Http\Controllers\Cliente\PagoController as ClientePagoController;
use App\Http\Controllers\Cliente\ComunicacionController;
use App\Http\Controllers\Cliente\MedidaController;
use App\Http\Controllers\Cliente\ObjetivoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('/cliente/dashboard', [DashboardController::class, 'index'])
        ->name('cliente.dashboard');

    Route::prefix('cliente/asistencias')->group(function () {
        Route::get('/', [ClienteAsistenciaController::class, 'index'])
            ->name('cliente.asistencias.index');
        Route::get('/', [ClienteAsistenciaController::class, 'index'])
            ->name('cliente.asistencias');
        Route::post('/entrada', [ClienteAsistenciaController::class, 'registrarEntrada'])
            ->name('cliente.asistencias.entrada');
        Route::post('/salida/{asistencia}', [ClienteAsistenciaController::class, 'registrarSalida'])
            ->name('cliente.asistencias.salida');
    });

    Route::prefix('cliente/rutinas')->group(function () {
        Route::get('/actual', [RutinaController::class, 'actual'])
            ->name('cliente.rutinas.actual');
        Route::get('/historial', [RutinaController::class, 'historial'])
            ->name('cliente.rutinas.historial');
        Route::get('/ejercicios', [RutinaController::class, 'ejercicios'])
            ->name('cliente.rutinas.ejercicios');
        Route::get('/ejercicios/{id}', [RutinaController::class, 'ejercicioDetalles'])
            ->name('cliente.rutinas.ejercicio');
        Route::put('/{rutina}/progreso', [RutinaController::class, 'actualizarProgreso'])
            ->name('cliente.rutinas.progreso');
        Route::post('/{rutina}/solicitar-cambio', [RutinaController::class, 'solicitarCambio'])
            ->name('cliente.rutinas.solicitar-cambio');
    });

    Route::prefix('cliente/nutricion')->group(function () {
        Route::get('/', [App\Http\Controllers\Cliente\NutricionController::class, 'actual'])
            ->name('cliente.nutricion');
        Route::get('/historial', [App\Http\Controllers\Cliente\NutricionController::class, 'historial'])
            ->name('cliente.nutricion.historial');
        Route::get('/{nutricion}', [App\Http\Controllers\Cliente\NutricionController::class, 'show'])
            ->name('cliente.nutricion.show');
    });

    Route::get('/cliente/membresia', [App\Http\Controllers\Cliente\MembresiaController::class, 'index'])
        ->name('cliente.membresia');

    Route::middleware(['auth', 'role:cliente'])->prefix('cliente/perfil')->name('cliente.perfil.')->group(function () {
        Route::get('/informacion', [App\Http\Controllers\Cliente\PerfilController::class, 'informacion'])->name('informacion');
        Route::get('/medidas', [App\Http\Controllers\Cliente\MedidaController::class, 'index'])->name('medidas');
        Route::post('/medidas', [App\Http\Controllers\Cliente\MedidaController::class, 'store'])->name('medidas.store');
        Route::get('/objetivos', [App\Http\Controllers\Cliente\ObjetivoController::class, 'index'])->name('objetivos');
        Route::post('/objetivos', [App\Http\Controllers\Cliente\ObjetivoController::class, 'store'])->name('objetivos.store');
        Route::put('/actualizar', [App\Http\Controllers\Cliente\PerfilController::class, 'actualizar'])->name('actualizar');
    });

    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/perfil', [OnboardingController::class, 'perfil'])->name('perfil');
        Route::post('/perfil', [OnboardingController::class, 'storePerfil'])->name('perfil.store');
        Route::get('/medidas', [OnboardingController::class, 'medidas'])->name('medidas');
        Route::post('/medidas', [OnboardingController::class, 'storeMedidas'])->name('medidas.store');
        Route::get('/objetivos', [OnboardingController::class, 'objetivos'])->name('objetivos');
        Route::post('/objetivos', [OnboardingController::class, 'storeObjetivos'])->name('objetivos.store');
        Route::get('/tour', [OnboardingController::class, 'tour'])->name('tour');
        Route::post('/tour/complete', [OnboardingController::class, 'completeTour'])->name('tour.complete');
    });

    Route::prefix('cliente/pagos')->group(function () {
        Route::get('/', [ClientePagoController::class, 'index'])->name('cliente.pagos.index');
        Route::post('/', [ClientePagoController::class, 'store'])->name('cliente.pagos.store');
        Route::get('/{pago}', [ClientePagoController::class, 'show'])->name('cliente.pagos.show');
        Route::get('/{pago}/info', [App\Http\Controllers\Cliente\PagoController::class, 'info'])
            ->middleware(['auth'])
            ->name('cliente.pagos.info');
    });

    Route::prefix('cliente/comunicacion')->group(function () {
        Route::get('/', [ComunicacionController::class, 'index'])
            ->name('cliente.comunicacion.index');
        Route::post('/enviar-mensaje', [ComunicacionController::class, 'enviarMensaje'])
            ->name('cliente.comunicacion.enviar-mensaje');
        Route::post('/notificaciones/{id}/marcar-leida', [ComunicacionController::class, 'marcarNotificacionLeida'])
            ->name('cliente.comunicacion.marcar-leida');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('membresias', MembresiaController::class);
    Route::post('membresias/{membresia}/registrar-visita', [MembresiaController::class, 'registrarVisita'])
        ->name('membresias.registrar-visita');
    Route::resource('pagos', PagoController::class);
    Route::post('/pagos/{pago}/aprobar', [PagoController::class, 'aprobar'])->name('pagos.aprobar');
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
    Route::post('asistencias/{asistencia}/registrar-salida', [AsistenciaController::class, 'registrarSalida'])
        ->name('asistencias.registrar-salida');
    Route::resource('nutricion', NutricionController::class);
    Route::resource('implementos', ImplementoController::class);
    Route::resource('duenos-gimnasio', DuenoGimnasioController::class)
        ->middleware(['auth', 'verified']);
    Route::resource('gimnasios', GimnasioController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('pagos-gimnasios', PagoGimnasioController::class);
    Route::resource('tipos-membresia', TipoMembresiaController::class);
    
    Route::patch('tipos-membresia/{tiposMembresia}/estado', [TipoMembresiaController::class, 'cambiarEstado'])
        ->name('tipos-membresia.cambiar-estado');

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
    });

    Route::get('/completar-registro', [RegisterController::class, 'completarRegistro'])->name('completar.registro');
    Route::get('/completar-registro/cliente', [RegisterController::class, 'mostrarFormularioCliente'])->name('completar.registro.cliente.form');
    Route::get('/completar-registro/empleado', [RegisterController::class, 'mostrarFormularioEmpleado'])->name('completar.registro.empleado.form');
    Route::get('/completar-registro/dueno', [RegisterController::class, 'mostrarFormularioDueno'])->name('completar.registro.dueno.form');

    Route::post('/completar-registro/cliente', [RegisterController::class, 'completarRegistroCliente'])->name('completar.registro.cliente.submit');

    Route::post('/completar-registro/empleado', [RegisterController::class, 'completarRegistroEmpleado'])->name('completar.registro.empleado');
    Route::post('/completar-registro/dueno', [RegisterController::class, 'completarRegistroDueno'])->name('completar.registro.dueno');
    Route::post('/completar-registro/dueno/guardar-paso', [RegisterController::class, 'guardarPasoDueno'])->name('guardar.paso.dueno');

    Route::post('/cliente/completar-registro', [App\Http\Controllers\Auth\ClienteRegistroController::class, 'completarRegistro'])->name('completar.registro.cliente');
    Route::post('/cliente/completar-registro/guardar-paso', [App\Http\Controllers\Auth\ClienteRegistroController::class, 'guardarPaso'])->name('guardar.paso.cliente');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register/cliente', [RegisterController::class, 'registerCliente'])->name('register.cliente');
    Route::post('/register/dueno', [RegisterController::class, 'registerDueno'])->name('register.dueno');
    Route::post('/register/gimnasio', [RegisterController::class, 'registerGimnasio'])->name('register.gimnasio');
    Route::post('/register/empleado', [RegisterController::class, 'registerEmpleado'])->name('register.empleado');
});

// Rutas para Pagos
Route::get('/api/membresias/{membresia}/pagos', [MembresiaController::class, 'pagos'])->name('membresias.pagos');

require __DIR__.'/auth.php';
