<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';
    protected $primaryKey = 'id_notificacion';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'contenido',
        'link',
        'leida',
        'fecha_lectura'
    ];

    protected $casts = [
        'leida' => 'boolean',
        'fecha_lectura' => 'datetime'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} 