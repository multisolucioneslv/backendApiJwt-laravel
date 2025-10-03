<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telegram extends Model
{
    protected $fillable = [
        'telegramID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
