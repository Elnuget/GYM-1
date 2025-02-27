<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mensaje extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mensajes';
    protected $primaryKey = 'id_mensaje';

    protected $fillable = [
        'emisor_id',
        'receptor_id',
        'contenido',
        'leido',
        'fecha_lectura'
    ];

    protected $casts = [
        'leido' => 'boolean',
        'fecha_lectura' => 'datetime'
    ];

    public function emisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'emisor_id');
    }

    public function receptor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receptor_id');
    }
} 