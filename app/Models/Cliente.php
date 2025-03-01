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
        'user_id',
        'gimnasio_id',
        'nombre',
        'email',
        'fecha_nacimiento',
        'telefono',
        'genero',
        'ocupacion'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gimnasio()
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id', 'id_gimnasio');
    }

    public function onboardingProgress()
    {
        return $this->hasOne(OnboardingProgress::class, 'cliente_id', 'id_cliente');
    }
}
