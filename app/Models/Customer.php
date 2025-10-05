<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone_id',
        'telegram_id',
        'user_id',
        'sex_id',
        'address',
    ];

    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }
    
    public function telegram()
    {
        return $this->belongsTo(Telegram::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    public function sex()
    {
        return $this->belongsTo(Sex::class);
    }
    
    /**
     * Cliente tiene muchas cotizaciones
     */
    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class, 'customer_id');
    }
}
