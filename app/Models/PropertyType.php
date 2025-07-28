<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PropertyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'property_types_features')
            ->withPivot('is_required_for_type', 'order_for_type')
            ->withTimestamps()
            ->orderBy('order_for_type');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function getFullNameAttribute(): string
    {
        return ($this->category ? "{$this->category->name} - " : '') . $this->name;
    }

    public function getFeaturesBySections()
    {
        return $this->features()
            ->with('featureSection')
            ->get()
            ->groupBy('featureSection.name')
            ->map(function ($features, $sectionName) {
                return [
                    'section_name' => $sectionName,
                    'features' => $features->map(function ($feature) {
                        return [
                            'id' => $feature->id,
                            'name' => $feature->name,
                            'is_required' => $feature->pivot->is_required_for_type,
                            'order' => $feature->pivot->order_for_type,
                        ];
                    })->sortBy('order')->values()
                ];
            });
    }

    public function getAssignedSections()
    {
        return $this->features()
            ->with('featureSection')
            ->get()
            ->pluck('featureSection')
            ->unique('id')
            ->sortBy('order')
            ->values();
    }

    public function hasFeature($featureId): bool
    {
        return $this->features()->where('features.id', $featureId)->exists();
    }

    public function isFeatureRequired($featureId): bool
    {
        $feature = $this->features()->where('features.id', $featureId)->first();
        return $feature ? $feature->pivot->is_required_for_type : false;
    }

    protected static function booted()
    {
        static::creating(function ($propertyType) {
            if (empty($propertyType->slug)) {
                $propertyType->slug = Str::slug($propertyType->name);
            }
        });

        static::updating(function ($propertyType) {
            if (empty($propertyType->slug)) {
                $propertyType->slug = Str::slug($propertyType->name);
            }
        });
    }
}
