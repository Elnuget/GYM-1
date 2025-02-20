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
        'dueno_id',
        'nombre',
        'direccion',
        'telefono'
    ];

    public function dueno()
    {
        return $this->belongsTo(DuenoGimnasio::class, 'dueno_id', 'id_dueno');
    }
}
