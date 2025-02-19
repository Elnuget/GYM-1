<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuenoGimnasio extends Model
{
    use HasFactory;

    protected $table = 'duenos_gimnasios';
    protected $primaryKey = 'id_dueno';

    protected $fillable = [
        'user_id',
        'nombre_comercial',
        'telefono',
        'direccion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} 