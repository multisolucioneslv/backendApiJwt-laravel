<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'numero_cotizacion',
        'customer_id',
        'user_id',
        'fecha_cotizacion',
        'fecha_vencimiento',
        'estado',
        'subtotal',
        'impuesto',
        'descuento',
        'total',
        'notas',
        'terminos',
    ];

    protected $casts = [
        'fecha_cotizacion' => 'date',
        'fecha_vencimiento' => 'date',
        'subtotal' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relaciones

    /**
     * Cotización pertenece a un cliente
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Cotización pertenece a un usuario (quien la creó)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cotización tiene muchos items
     */
    public function items()
    {
        return $this->hasMany(CotizacionItem::class, 'cotizacion_id');
    }

    // Métodos auxiliares

    /**
     * Generar número de cotización único
     */
    public static function generarNumeroCotizacion()
    {
        $year = date('Y');
        $ultimaCotizacion = self::where('numero_cotizacion', 'like', 'COT-' . $year . '-%')
            ->orderBy('numero_cotizacion', 'desc')
            ->first();

        $siguienteNumero = $ultimaCotizacion ? (intval(substr($ultimaCotizacion->numero_cotizacion, -4)) + 1) : 1;

        return 'COT-' . $year . '-' . str_pad($siguienteNumero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calcular total de la cotización
     */
    public function calcularTotal()
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->total = $this->subtotal + $this->impuesto - $this->descuento;
        $this->save();
    }

    /**
     * Verificar si la cotización está vencida
     */
    public function estaVencida()
    {
        return $this->fecha_vencimiento < now() && $this->estado !== 'aprobada';
    }
}
