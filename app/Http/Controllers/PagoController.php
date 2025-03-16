<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Membresia;
use App\Models\MetodoPago;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_membresia' => 'required|exists:membresias,id_membresia',
            'id_usuario' => 'required|exists:users,id',
            'monto' => 'required|numeric|min:0',
            'id_metodo_pago' => 'required|exists:metodos_pago,id_metodo_pago',
            'estado_pago' => 'required|in:pendiente,aprobado,rechazado',
            'notas' => 'nullable|string|max:255',
            'fecha_pago' => 'nullable|date'
        ]);

        DB::beginTransaction();
        try {
            $pago = new Pago();
            $pago->id_membresia = $request->id_membresia;
            $pago->id_usuario = $request->id_usuario;
            $pago->monto = $request->monto;
            $pago->id_metodo_pago = $request->id_metodo_pago;
            $pago->estado = $request->estado_pago;
            $pago->notas = $request->notas;
            $pago->fecha_pago = $request->fecha_pago ?? now();
            
            // Si el estado es aprobado, establecer la fecha de aprobación
            if ($pago->estado === 'aprobado') {
                $pago->fecha_aprobacion = now();
            }
            
            // Guardar el pago
            $pago->save();
            
            // Si el estado es aprobado, actualizar el saldo pendiente de la membresía
            if ($pago->estado === 'aprobado') {
                $membresia = Membresia::findOrFail($request->id_membresia);
                $nuevoSaldo = $membresia->saldo_pendiente - $pago->monto;
                
                // Asegurarse de que el saldo no sea negativo
                $membresia->saldo_pendiente = max(0, $nuevoSaldo);
                $membresia->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pago registrado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
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

    /**
     * Approve a pending payment.
     */
    public function aprobar(Pago $pago)
    {
        if ($pago->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Solo se pueden aprobar pagos pendientes');
        }

        DB::beginTransaction();
        try {
            // Actualizar el pago
            $pago->estado = 'aprobado';
            $pago->fecha_aprobacion = now();
            $pago->save();

            // Actualizar el saldo pendiente de la membresía
            $membresia = $pago->membresia;
            $nuevoSaldo = $membresia->saldo_pendiente - $pago->monto;
            
            // Asegurarse de que el saldo no sea negativo
            $membresia->saldo_pendiente = max(0, $nuevoSaldo);
            $membresia->save();

            DB::commit();
            return redirect()->back()->with('success', 'Pago aprobado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al aprobar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Pago $pago)
    {
        if ($pago->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Solo se pueden eliminar pagos pendientes');
        }

        try {
            $pago->delete();
            return redirect()->back()->with('success', 'Pago eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el pago: ' . $e->getMessage());
        }
    }
}