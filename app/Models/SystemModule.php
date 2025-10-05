<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemModule extends Model
{
    protected $fillable = [
        'system_type_id',
        'name',
        'slug',
        'description',
        'route',
        'icon',
        'color',
        'permissions',
        'is_active',
        'is_required',
        'sort_order'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
        'is_required' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Relación con el tipo de sistema
     */
    public function systemType(): BelongsTo
    {
        return $this->belongsTo(SystemType::class);
    }

    /**
     * Scope para módulos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para módulos requeridos
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope para ordenar por sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
