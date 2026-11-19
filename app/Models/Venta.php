<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'usuario_id',
        'fecha_venta',
        'total',
        'estado',
        'motivo_anulacion',
        'anulada_por',
        'anulada_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha_venta' => 'date',
            'total' => 'decimal:2',
            'anulada_at' => 'datetime',
        ];
    }

    protected function numero(): Attribute
    {
        return Attribute::get(fn () => str_pad($this->id, 6, '0', STR_PAD_LEFT));
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function anuladaPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'anulada_por');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }
}