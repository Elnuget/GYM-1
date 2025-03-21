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
        'ocupacion',
        'direccion',
        'peso',
        'altura',
        'objetivo_fitness',
        'condiciones_medicas',
        'nivel_actividad',
        'foto_perfil',
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

    public function medidasCorporales()
    {
        return $this->hasMany(MedidaCorporal::class, 'cliente_id', 'id_cliente');
    }
    
    /**
     * Obtiene las membresías asociadas al usuario del cliente.
     */
    public function membresias()
    {
        return $this->user->membresias();
    }
    
    /**
     * Verifica si el cliente tiene alguna membresía activa.
     */
    public function tieneMembresiaActiva()
    {
        return \App\Models\Membresia::where('id_usuario', $this->user_id)->exists();
    }
}
