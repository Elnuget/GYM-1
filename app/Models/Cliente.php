<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'gimnasio_id',
        'nombre',
        'email',
        'telefono',
        'fecha_nacimiento'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    public function gimnasio()
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id', 'id_gimnasio');
    }
}
