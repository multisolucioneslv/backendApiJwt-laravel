<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected $appends = ['phone_number', 'telegram_username', 'sex_name'];

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

    /**
     * Accessor para obtener el número de teléfono directamente
     */
    protected function phoneNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->phone?->phone ?? null,
        );
    }

    /**
     * Accessor para obtener el nombre de usuario de Telegram
     */
    protected function telegramUsername(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->telegram?->username ?? null,
        );
    }

    /**
     * Accessor para obtener el nombre del sexo
     */
    protected function sexName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->sex?->name ?? null,
        );
    }
}
