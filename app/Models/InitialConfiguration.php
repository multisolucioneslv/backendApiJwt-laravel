<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'is_required',
        'is_public'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Obtener el valor convertido según su tipo
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
     * Establecer el valor según su tipo
     */
    public function setConvertedValueAttribute($value)
    {
        switch ($this->type) {
            case 'boolean':
                $this->value = $value ? 'true' : 'false';
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
     * Obtener configuración por clave
     */
    public static function getByKey($key, $default = null)
    {
        $config = static::where('key', $key)->first();
        return $config ? $config->converted_value : $default;
    }

    /**
     * Establecer configuración por clave
     */
    public static function setByKey($key, $value, $type = 'string', $description = null)
    {
        $config = static::where('key', $key)->first();
        
        if ($config) {
            $config->type = $type;
            $config->description = $description;
            // Establecer el valor según el tipo
            switch ($type) {
                case 'boolean':
                    $config->value = $value ? 'true' : 'false';
                    break;
                case 'integer':
                    $config->value = (string) $value;
                    break;
                case 'json':
                    $config->value = json_encode($value);
                    break;
                default:
                    $config->value = (string) $value;
            }
            $config->save();
        } else {
            // Preparar el valor según el tipo para crear nuevo registro
            $valueToStore = $value;
            switch ($type) {
                case 'boolean':
                    $valueToStore = $value ? 'true' : 'false';
                    break;
                case 'integer':
                    $valueToStore = (string) $value;
                    break;
                case 'json':
                    $valueToStore = json_encode($value);
                    break;
                default:
                    $valueToStore = (string) $value;
            }
            
            $config = static::create([
                'key' => $key,
                'value' => $valueToStore,
                'type' => $type,
                'description' => $description
            ]);
        }
        
        return $config;
    }
}
