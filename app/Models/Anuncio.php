<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anuncio extends Model
{
    use HasFactory;

    protected $table = 'anuncios';
    protected $primaryKey = 'id_anuncio';

    protected $fillable = [
        'gimnasio_id',
        'titulo',
        'contenido',
        'imagen_url',
        'importante',
        'fecha_publicacion',
        'fecha_expiracion',
        'activo'
    ];

    protected $casts = [
        'importante' => 'boolean',
        'activo' => 'boolean',
        'fecha_publicacion' => 'datetime',
        'fecha_expiracion' => 'datetime'
    ];

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id', 'id_gimnasio');
    }
} 