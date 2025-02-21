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
        'dueno_id'
    ];

    public function dueno()
    {
        return $this->belongsTo(User::class, 'dueno_id', 'id');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'gimnasio_id', 'id_gimnasio');
    }
}
