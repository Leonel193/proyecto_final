<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';

    protected $fillable = [
        'producto_id',
        'detalle_compra_id',
        'numero_lote',
        'fecha_vencimiento',
        'fecha_ingreso',
        'cantidad',
    ];

    protected function casts(): array
    {
        return [
            'fecha_vencimiento' => 'date',
            'fecha_ingreso' => 'date',
            'cantidad' => 'integer',
        ];
    }

    // Producto al que pertenece el lote
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // Detalle de compra que originó el lote
    public function detalleCompra(): BelongsTo
    {
        return $this->belongsTo(
            DetalleCompra::class,
            'detalle_compra_id'
        );
    }

    // Detalles de venta que utilizaron este lote
    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'lote_id');
    }
}

