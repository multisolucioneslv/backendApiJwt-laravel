<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telegram extends Model
{
    protected $fillable = [
        'telegramID',
    ];

    /**
     * Un telegram puede pertenecer a muchos usuarios
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Un telegram puede pertenecer a muchos clientes
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
