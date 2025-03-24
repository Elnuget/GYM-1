<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gimnasio extends Model
{
    use HasFactory;

    protected $table = 'gimnasios';
    protected $primaryKey = 'id_gimnasio';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'descripcion',
        'dueno_id',
        'logo',
        'estado'
    ];

    public function dueno()
    {
        return $this->belongsTo(User::class, 'dueno_id');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'gimnasio_id', 'id_gimnasio');
    }
}
