<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sex extends Model
{
    protected $fillable = [
        'name',
        'inicial',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}   
