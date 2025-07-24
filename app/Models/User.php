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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, HasRoles;

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
        'status',
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

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function propertyContacts(): HasManyThrough
    {
        return $this->hasManyThrough(
            PropertyContact::class,
            Property::class,
            'user_id',
            'property_id',
            'id',
            'id'
        );
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function favoriteProperties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'user_favorite_properties', 'user_id', 'property_id')->withTimestamps();
    }

    public function hasFavorited(Property $property): bool
    {
        return $this->favoriteProperties()->where('property_id', $property->id)->exists();
    }
}
