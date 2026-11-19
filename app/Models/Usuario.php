<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombres',
        'apellidos',
        'usuario',
        'telefono',
        'correo',
        'password',
        'rol',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'activo' => 'boolean'
        ];
    }
    public function espropietaria(): bool
    {
        return $this->rol === 'propietaria';
    }

    public function puedeAnularVenta(Venta $venta): bool
    {
        if ($venta->estado === 'anulada') {
            return false;
        }
        if ($this->espropietaria()) {
            return true;
        }

        return $venta->usuario_id === $this->id 
        && Carbon::parse($venta->fecha_venta)->isToday();
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class, 'usuario_id');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'usuario_id');
    }
}
