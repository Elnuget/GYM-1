<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Membresia;
use App\Models\MetodoPago;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with([
            'membresia.tipoMembresia', 
            'usuario', 
            'metodoPago'
        ])
            ->orderBy('id_pago', 'desc')
            ->paginate(10);
        $usuarios = User::all();
        $membresias = Membresia::with('tipoMembresia', 'usuario')->get();
        $metodosPago = MetodoPago::where('activo', true)->get();
        
        return view('pagos.index', compact('pagos', 'usuarios', 'membresias', 'metodosPago'));
    }

    public function create()
    {
        $membresias = Membresia::all();
        $metodosPago = MetodoPago::where('activo', true)->get();
        return view('pagos.create', compact('membresias', 'metodosPago'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_membresia' => 'required|exists:membresias,id_membresia',
            'id_usuario' => 'required|exists:users,id',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB máx
            'notas' => 'nullable|string',
            'fecha_aprobacion' => 'nullable|date'
        ]);

        $validated['comprobante_url'] = null;

        if ($request->hasFile('comprobante')) {
            $file = $request->file('comprobante');
            $filename = 'comprobante_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('comprobantes', $filename, 'public');
            $validated['comprobante_url'] = $path;
        }

        if ($validated['estado'] === 'aprobado') {
            $validated['fecha_aprobacion'] = now();
        }
        
        Pago::create($validated);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago registrado exitosamente');
    }

    public function edit(Pago $pago)
    {
        $membresias = Membresia::all();
        $metodosPago = MetodoPago::where('activo', true)->get();
        $usuarios = User::all();
        return view('pagos.edit', compact('pago', 'membresias', 'metodosPago', 'usuarios'));
    }

    public function update(Request $request, Pago $pago)
    {
        $validated = $request->validate([
            'id_membresia' => 'required|exists:membresias,id_membresia',
            'id_usuario' => 'required|exists:users,id',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'estado' => 'required|in:pendiente,aprobado,rechazado',
            'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB máx
            'notas' => 'nullable|string',
            'fecha_aprobacion' => 'nullable|date'
        ]);

        if ($request->hasFile('comprobante')) {
            // Eliminar el archivo antiguo si existe
            if ($pago->comprobante_url && Storage::disk('public')->exists($pago->comprobante_url)) {
                Storage::disk('public')->delete($pago->comprobante_url);
            }
            
            $file = $request->file('comprobante');
            $filename = 'comprobante_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('comprobantes', $filename, 'public');
            $validated['comprobante_url'] = $path;
        }

        if ($validated['estado'] === 'aprobado' && !$pago->fecha_aprobacion) {
            $validated['fecha_aprobacion'] = now();
        }

        $pago->update($validated);

        return redirect()->route('pagos.index')
            ->with('success', 'Pago actualizado exitosamente');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();

        return redirect()->route('pagos.index')
            ->with('success', 'Pago eliminado exitosamente');
    }

    public function aprobar(Request $request, Pago $pago)
    {
        if ($pago->estado !== 'pendiente') {
            return response()->json(['error' => 'El pago no está en estado pendiente'], 400);
        }

        try {
            \DB::transaction(function () use ($pago) {
                // Actualizar el estado del pago
                $pago->update([
                    'estado' => 'aprobado',
                    'fecha_aprobacion' => now()
                ]);

                // Actualizar el saldo pendiente de la membresía
                $membresia = $pago->membresia;
                $membresia->saldo_pendiente = max(0, $membresia->saldo_pendiente - $pago->monto);
                $membresia->save();
            });

            return response()->json([
                'message' => 'Pago aprobado exitosamente',
                'pago' => $pago->load(['membresia', 'usuario', 'metodoPago'])
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al aprobar el pago'], 500);
        }
    }
}