<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_application_id', 
        'phone_number',
        'whatsapp_number',
        'contact_email',
        'identification_type',
        'identification_path',
        'license_path',
        'years_experience',
        'real_estate_company',
        'rfc',
        'status',
        'approved_at',
    ];

    /**
     * Estados disponibles
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_PENDING = 'pending';

    public const STATUS_OPTIONS = [
        self::STATUS_ACTIVE => 'Activo',
        self::STATUS_INACTIVE => 'Inactivo',
        self::STATUS_PENDING => 'Pendiente',
    ];

    /**
     * Relaciones
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(UserApplication::class, 'user_application_id');
    }

    /**
     * Accesor para el estado legible
     */
    public function getStatusHumanReadableAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? 'Desconocido';
    }

    /**
     * Accesor para obtener el tipo de perfil (basado en rol Spatie)
     */
    public function getBusinessTypeAttribute(): ?string
    {
        if (!$this->user) return null;

        return match (true) {
            $this->user->hasRole('agent') => 'Agente Inmobiliario',
            $this->user->hasRole('owner') => 'Dueño Directo',
            $this->user->hasRole('real_estate_company') => 'Inmobiliaria / Desarrolladora',
            default => null
        };
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Métodos de negocio
     */
    public function activate(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'approved_at' => now()
        ]);
    }

    public function deactivate(): void
    {
        $this->update(['status' => self::STATUS_INACTIVE]);
    }
}
