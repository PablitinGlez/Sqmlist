<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, HasRoles;

    // --- Constantes para el estado del usuario ---
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_OPTIONS = [
        self::STATUS_ACTIVE => 'Activo',
        self::STATUS_INACTIVE => 'Inactivo',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'external_id',
        'external_auth',
        'email_verified_at',
        'status', // ¡NUEVO! Añadir la columna 'status' aquí
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Por ahora, solo nos aseguramos de que el usuario tenga el rol 'admin' para el panel 'admin'.
        // La lógica para deshabilitar el acceso a usuarios inactivos se añadirá en un paso posterior.
        if ($panel->getId() === 'admin') {
            return $this->hasRole('admin');
        }

        if ($panel->getId() === 'advertiser') {
            return $this->hasAnyRole(['owner', 'agent', 'real_estate_company']);
        }
        
        return false;
    }

    public function hasSocialLogin(): bool
    {
        return !is_null($this->external_id) && !is_null($this->external_auth);
    }

    public function hasSocialLoginWithoutPassword(): bool
    {
        return $this->hasSocialLogin() && is_null($this->password);
    }

    public function canUpdatePassword(): bool
    {
        return !$this->hasSocialLogin() || !is_null($this->password);
    }

    public function profileDetails()
    {
        return $this->hasOne(ProfileDetails::class);
    }

    public function userApplications()
    {
        return $this->hasMany(UserApplication::class);
    }

    public function isAgent(): bool
    {
        return $this->hasRole('agent');
    }

    public function isOwner(): bool
    {
        return $this->hasRole('owner');
    }

    public function isRealEstateCompany(): bool
    {
        return $this->hasRole('real_estate_company');
    }

    public function hasBusinessProfile(): bool
    {
        return $this->hasAnyRole(['owner', 'agent', 'real_estate_company']);
    }

    public function getLatestUserApplication()
    {
        return $this->userApplications()->latest()->first();
    }

    public function getPendingUserApplication()
    {
        return $this->userApplications()
            ->where('status', UserApplication::STATUS_PENDING)
            ->first();
    }

    public function hasPendingOrApprovedApplication(): bool
    {
        $latest = $this->getLatestUserApplication();

        return $latest && in_array($latest->status, [
            UserApplication::STATUS_PENDING,
            UserApplication::STATUS_APPROVED
        ]);
    }

    public function canSubmitNewApplication(): bool
    {
        if ($this->hasBusinessProfile()) {
            return false;
        }

        if ($this->hasPendingOrApprovedApplication()) {
            return false;
        }

        $latest = $this->getLatestUserApplication();
        return !$latest || $latest->status === UserApplication::STATUS_REJECTED;
    }

    public function getBusinessPanelRoute(): ?string
    {
        return match (true) {
            $this->isAgent() => route('agent.dashboard'),
            $this->isOwner() => route('owner.dashboard'),
            $this->isRealEstateCompany() => route('company.dashboard'),
            default => null
        };
    }

    public function getBusinessTypeLabel(): ?string
    {
        return match (true) {
            $this->isAgent() => 'Agente Inmobiliario',
            $this->isOwner() => 'Dueño Directo',
            $this->isRealEstateCompany() => 'Inmobiliaria',
            default => null
        };
    }

    // --- NUEVAS RELACIONES PARA EL ANUNCIANTE ---

    /**
     * Un usuario puede tener muchas propiedades.
     * Esta relación es clave para obtener las propiedades de un anunciante.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Un usuario puede tener muchos mensajes de contacto, a través de sus propiedades.
     * Esta relación es perfecta para el panel del anunciante.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function propertyContacts(): HasManyThrough
    {
        return $this->hasManyThrough(
            PropertyContact::class, // El modelo final que queremos obtener
            Property::class,        // El modelo intermedio (a través del cual llegamos a PropertyContact)
            'user_id',              // La clave foránea en la tabla `properties` que apunta a `users`
            'property_id',          // La clave foránea en la tabla `property_contacts` que apunta a `properties`
            'id',                   // La clave local en la tabla `users` (id del usuario)
            'id'                    // La clave local en la tabla `properties` (id de la propiedad)
        );
    }

    // --- Métodos auxiliares para el estado del usuario ---
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }
}
