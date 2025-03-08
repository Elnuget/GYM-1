<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Membresia;
use App\Models\MetodoPago;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::where('id_usuario', auth()->id())
            ->with(['membresia', 'metodoPago'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $membresias = Membresia::where('id_usuario', auth()->id())
            ->where('fecha_vencimiento', '>', now())
            ->with('tipoMembresia')
            ->get();

        $metodosPago = MetodoPago::where('activo', true)->get();

        return view('cliente.pagos.index', compact('pagos', 'membresias', 'metodosPago'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_membresia' => 'required|exists:membresias,id_membresia',
            'monto' => 'required|numeric|min:0',
            'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
            'comprobante' => 'required_if:metodo_pago,transferencia|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'notas' => 'nullable|string|max:500'
        ]);

        $pago = new Pago();
        $pago->id_membresia = $request->id_membresia;
        $pago->id_usuario = auth()->id();
        $pago->monto = $request->monto;
        $pago->fecha_pago = now();
        $pago->id_metodo_pago = $request->id_metodo_pago;
        $pago->estado = 'pendiente';
        $pago->notas = $request->notas;

        // Manejo del comprobante
        if ($request->hasFile('comprobante')) {
            $file = $request->file('comprobante');
            $filename = 'comprobante_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('comprobantes', $filename, 'public');
            $pago->comprobante_url = $path;
        }

        $pago->save();

        return redirect()->route('cliente.pagos.index')
            ->with('success', 'Pago registrado correctamente. Esperando aprobación.');
    }

    public function show(Pago $pago)
    {
        // Verificar que el pago pertenezca al usuario autenticado
        if ($pago->id_usuario != auth()->id()) {
            abort(403, 'No tienes permiso para ver este pago.');
        }
        
        return view('cliente.pagos.show', compact('pago'));
    }

    /**
     * Obtiene la información de un pago específico en formato JSON.
     *
     * @param  \App\Models\Pago  $pago
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Pago $pago)
    {
        // Verificar que el pago pertenezca al usuario autenticado
        if ($pago->id_usuario != auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        
        // Cargar relaciones necesarias
        $pago->load(['membresia.tipoMembresia', 'metodoPago']);
        
        return response()->json($pago);
    }
} 