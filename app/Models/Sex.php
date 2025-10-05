<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sex extends Model
{
    protected $fillable = [
        'name',
        'inicial',
    ];

    /**
     * Un sexo puede pertenecer a muchos usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Un sexo puede pertenecer a muchos clientes
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
