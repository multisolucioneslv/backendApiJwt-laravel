<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSystemAccess extends Model
{
    protected $fillable = [
        'user_id',
        'system_type_id',
        'module_permissions',
        'is_admin',
        'is_active',
        'granted_at',
        'expires_at'
    ];

    protected $casts = [
        'module_permissions' => 'array',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'granted_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el tipo de sistema
     */
    public function systemType(): BelongsTo
    {
        return $this->belongsTo(SystemType::class);
    }

    /**
     * Scope para accesos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para administradores
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope para accesos no expirados
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Verificar si el acceso está activo y no expirado
     */
    public function isValid(): bool
    {
        return $this->is_active && 
               ($this->expires_at === null || $this->expires_at > now());
    }
}
