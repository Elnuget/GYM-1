<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Membresia;
use App\Models\MetodoPago;
use App\Models\User;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['membresia', 'usuario', 'metodoPago'])->paginate(10);
        $usuarios = User::all();
        $membresias = Membresia::all();
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
            'comprobante_url' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
            'fecha_aprobacion' => 'nullable|date'
        ]);

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
            'comprobante_url' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
            'fecha_aprobacion' => 'nullable|date'
        ]);

        if ($request->hasFile('comprobante')) {
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
}