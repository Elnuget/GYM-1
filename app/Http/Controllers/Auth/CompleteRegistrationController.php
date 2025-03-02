/**
     * Crea una membresía inicial para el gimnasio recién creado
     */
    protected function crearMembresiaInicial($request, $gimnasio)
    {
        $tipoMembresia = TipoMembresia::create([
            'gimnasio_id' => $gimnasio->id_gimnasio,
            'nombre' => $request->membresia_nombre,
            'descripcion' => $request->membresia_descripcion,
            'precio' => $request->membresia_precio,
            'tipo' => $request->membresia_tipo,
            'estado' => true,
        ]);

        // Según el tipo, asignamos duración o número de visitas
        if ($request->membresia_tipo === 'visitas') {
            $tipoMembresia->numero_visitas = $request->membresia_visitas;
            $tipoMembresia->duracion_dias = null;
        } else {
            $tipoMembresia->duracion_dias = $request->membresia_duracion;
            $tipoMembresia->numero_visitas = null;
        }

        $tipoMembresia->save();

        return $tipoMembresia;
    } 