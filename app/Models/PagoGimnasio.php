<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoGimnasio extends Model
{
    use HasFactory;

    protected $table = 'pagos_gimnasios';
    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'dueno_id',
        'monto',
        'fecha_pago',
        'estado',
        'metodo_pago'
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2'
    ];

    public function dueno()
    {
        return $this->belongsTo(DuenoGimnasio::class, 'dueno_id', 'id_dueno');
    }
}
