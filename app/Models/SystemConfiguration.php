<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemConfiguration extends Model
{
    protected $fillable = [
        'system_type_id',
        'key',
        'value',
        'type',
        'description',
        'is_required',
        'is_public'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_public' => 'boolean'
    ];

    /**
     * Relación con el tipo de sistema
     */
    public function systemType(): BelongsTo
    {
        return $this->belongsTo(SystemType::class);
    }

    /**
     * Scope para configuraciones públicas
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope para configuraciones requeridas
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Obtener el valor convertido según el tipo
     */
    public function getConvertedValueAttribute()
    {
        switch ($this->type) {
            case 'boolean':
                return filter_var($this->value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $this->value;
            case 'json':
                return json_decode($this->value, true);
            default:
                return $this->value;
        }
    }

    /**
     * Establecer el valor según el tipo
     */
    public function setConvertedValue($value)
    {
        switch ($this->type) {
            case 'boolean':
                $this->value = $value ? '1' : '0';
                break;
            case 'integer':
                $this->value = (string) $value;
                break;
            case 'json':
                $this->value = json_encode($value);
                break;
            default:
                $this->value = (string) $value;
        }
    }
}
