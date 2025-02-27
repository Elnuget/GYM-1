<?php

namespace App\Policies;

use App\Models\Pago;
use App\Models\User;

class PagoPolicy
{
    public function view(User $user, Pago $pago)
    {
        return $user->id === $pago->id_usuario;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Pago $pago)
    {
        return $user->id === $pago->id_usuario && $pago->estado === 'pendiente';
    }
} 