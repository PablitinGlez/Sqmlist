<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'color'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function propertyTypes(): HasMany
    {
        return $this->hasMany(PropertyType::class);
    }

    public function activePropertyTypes(): HasMany
    {
        return $this->propertyTypes()->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    public function getFormattedNameAttribute(): string
    {
        return ucfirst($this->name);
    }

    public function getPropertyTypesCountAttribute(): int
    {
        return $this->propertyTypes()->count();
    }

    public function getRgbColorAttribute(): array
    {
        $hex = ltrim($this->color, '#');
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }
}
