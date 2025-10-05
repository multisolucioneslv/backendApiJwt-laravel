<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SystemType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'features',
        'required_tables',
        'is_active',
        'is_default',
        'icon',
        'color',
        'sort_order'
    ];

    protected $casts = [
        'features' => 'array',
        'required_tables' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Relación con los módulos del sistema
     */
    public function modules(): HasMany
    {
        return $this->hasMany(SystemModule::class);
    }

    /**
     * Relación con las configuraciones del sistema
     */
    public function configurations(): HasMany
    {
        return $this->hasMany(SystemConfiguration::class);
    }

    /**
     * Relación con el acceso de usuarios
     */
    public function userAccess(): HasMany
    {
        return $this->hasMany(UserSystemAccess::class);
    }

    /**
     * Obtener módulos activos
     */
    public function activeModules()
    {
        return $this->modules()->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Obtener configuraciones públicas
     */
    public function publicConfigurations()
    {
        return $this->configurations()->where('is_public', true);
    }

    /**
     * Scope para sistemas activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para sistema por defecto
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
