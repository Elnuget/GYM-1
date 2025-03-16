<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membresia;
use Exception;

class MembresiaController extends Controller
{
    /**
     * Obtiene una membresía con toda su información relacionada
     */
    public function show($id)
    {
        try {
            $membresia = Membresia::with([
                'usuario', 
                'tipoMembresia.gimnasio'
            ])->findOrFail($id);
            
            // Asegurar que tenemos los datos completos incluyendo las relaciones
            return response()->json($membresia);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la membresía',
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
