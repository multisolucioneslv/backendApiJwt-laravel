<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [
        'phone',
    ];

    /**
     * Un teléfono puede pertenecer a muchos usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Un teléfono puede pertenecer a muchos clientes
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
