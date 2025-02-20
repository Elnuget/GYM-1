<?php

namespace App\Http\Controllers;

use App\Models\PagoGimnasio;
use App\Models\DuenoGimnasio;
use Illuminate\Http\Request;

class PagoGimnasioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagos = PagoGimnasio::with('dueno')->get();
        $duenos = DuenoGimnasio::all();
        $metodos_pago = [
            'tarjeta_credito' => 'Tarjeta de Crédito',
            'efectivo' => 'Efectivo',
            'transferencia_bancaria' => 'Transferencia Bancaria'
        ];
        
        return view('pagos-gimnasios.index', compact('pagos', 'duenos', 'metodos_pago'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $duenos = DuenoGimnasio::all();
        $metodos_pago = ['tarjeta_credito' => 'Tarjeta de Crédito', 
                        'efectivo' => 'Efectivo', 
                        'transferencia_bancaria' => 'Transferencia Bancaria'];
        return view('pagos-gimnasios.create', compact('duenos', 'metodos_pago'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dueno_id' => 'required|exists:duenos_gimnasios,id_dueno',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'estado' => 'required|in:pagado,pendiente',
            'metodo_pago' => 'required|in:tarjeta_credito,efectivo,transferencia_bancaria',
        ]);

        PagoGimnasio::create($validated);

        return redirect()->route('pagos-gimnasios.index')
            ->with('success', 'Pago registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PagoGimnasio $pagosGimnasio)
    {
        return view('pagos-gimnasios.show', ['pago' => $pagosGimnasio]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PagoGimnasio $pagosGimnasio)
    {
        $duenos = DuenoGimnasio::all();
        $metodos_pago = ['tarjeta_credito' => 'Tarjeta de Crédito', 
                        'efectivo' => 'Efectivo', 
                        'transferencia_bancaria' => 'Transferencia Bancaria'];
        return view('pagos-gimnasios.edit', [
            'pago' => $pagosGimnasio,
            'duenos' => $duenos,
            'metodos_pago' => $metodos_pago
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PagoGimnasio $pagosGimnasio)
    {
        $validated = $request->validate([
            'dueno_id' => 'required|exists:duenos_gimnasios,id_dueno',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'estado' => 'required|in:pagado,pendiente',
            'metodo_pago' => 'required|in:tarjeta_credito,efectivo,transferencia_bancaria',
        ]);

        $pagosGimnasio->update($validated);

        return redirect()->route('pagos-gimnasios.index')
            ->with('success', 'Pago actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PagoGimnasio $pagosGimnasio)
    {
        $pagosGimnasio->delete();

        return redirect()->route('pagos-gimnasios.index')
            ->with('success', 'Pago eliminado exitosamente.');
    }
}
