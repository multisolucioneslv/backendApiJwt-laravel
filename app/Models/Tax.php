<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name',
        'rate',
        'is_active',
        'description'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean'
    ];
}
