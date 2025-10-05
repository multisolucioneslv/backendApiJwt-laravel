<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CotizacionItem extends Model
{
    use HasFactory;

    protected $table = 'cotizacion_items';

    protected $fillable = [
        'cotizacion_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'subtotal',
        'descripcion',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'descuento' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relaciones

    /**
     * Item pertenece a una cotización
     */
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }

    /**
     * Item pertenece a un producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Métodos auxiliares

    /**
     * Calcular subtotal del item
     */
    public function calcularSubtotal()
    {
        $this->subtotal = ($this->cantidad * $this->precio_unitario) - $this->descuento;
        $this->save();

        // Actualizar total de la cotización
        $this->cotizacion->calcularTotal();
    }

    /**
     * Método que se ejecuta antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->subtotal = ($item->cantidad * $item->precio_unitario) - $item->descuento;
        });

        static::saved(function ($item) {
            $item->cotizacion->calcularTotal();
        });

        static::deleted(function ($item) {
            $item->cotizacion->calcularTotal();
        });
    }
}
