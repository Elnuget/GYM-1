<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    public function run()
    {
        $metodos = [
            [
                'nombre_metodo' => 'efectivo',
                'descripcion' => 'Pago en efectivo',
                'activo' => true
                
            ],
            [
                'nombre_metodo' => 'tarjeta_credito',
                'descripcion' => 'Pago con tarjeta de crédito',
                'activo' => true
            ],
            [
                'nombre_metodo' => 'transferencia_bancaria',
                'descripcion' => 'Pago mediante transferencia bancaria',
                'activo' => true
            ]
        ];

        foreach ($metodos as $metodo) {
            MetodoPago::create($metodo);
        }
    }
} 