<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursale extends Model
{
    /** @use HasFactory<\Database\Factories\SucursaleFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'activo',
        'principal',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'principal' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}