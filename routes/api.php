<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MembresiaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas para membresías
Route::get('/membresias/{id}', [MembresiaController::class, 'show']);

// Ruta existente para pagos
Route::get('/membresias/{membresia}/pagos', function($membresia) {
    return \App\Models\Pago::where('id_membresia', $membresia)->with(['metodoPago'])->get();
}); 