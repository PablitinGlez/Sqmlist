<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserApplication extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const TYPE_OWNER = 'owner';
    public const TYPE_AGENT = 'agent';
    public const TYPE_REAL_ESTATE_COMPANY = 'real_estate_company';

    public const TYPE_OPTIONS = [
        self::TYPE_OWNER => 'Dueño Directo',
        self::TYPE_AGENT => 'Agente Inmobiliario',
        self::TYPE_REAL_ESTATE_COMPANY => 'Inmobiliaria / Desarrolladora',
    ];

    protected $fillable = [
        'user_id',
        'requested_user_type',
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
        'status_message',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profileDetails(): HasOne
    {
        return $this->hasOne(ProfileDetails::class);
    }

    public function getStatusHumanReadableAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_APPROVED => 'Aprobada',
            self::STATUS_REJECTED => 'Rechazada',
            default => 'Desconocido',
        };
    }

    public function getRequestedUserTypeHumanReadableAttribute(): string
    {
        return self::TYPE_OPTIONS[$this->requested_user_type] ?? 'Desconocido';
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function approve(): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'status_message' => 'Solicitud aprobada'
        ]);

        $this->user->assignRole($this->requested_user_type);

        $this->user->profileDetails()->create(
            $this->only([
                'phone_number',
                'whatsapp_number',
                'contact_email',
                'identification_type',
                'identification_path',
                'license_path',
                'years_experience',
                'real_estate_company',
                'rfc'
            ]) + [
                'status' => 'active',
                'approved_at' => now(),
                'user_application_id' => $this->id
            ]
        );
    }

    public function reject(string $message): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'status_message' => $message
        ]);

        $this->user->removeRole($this->requested_user_type);
        $this->profileDetails?->delete();
    }
}
