<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_section_id',
        'name',
        'slug',
        'description',
        'input_type',
        'data_type',
        'options',
        'unit',
        'default_value',
        'is_filterable',
        'is_searchable',
        'is_required',
        'order',
        'icon',
    ];

    protected $casts = [
        'options' => 'array',
        'is_filterable' => 'boolean',
        'is_searchable' => 'boolean',
        'is_required' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function featureSection(): BelongsTo
    {
        return $this->belongsTo(FeatureSection::class);
    }

    public function propertyFeatureValues(): HasMany
    {
        return $this->hasMany(PropertyFeatureValue::class);
    }

    public function propertyTypes(): BelongsToMany
    {
        return $this->belongsToMany(PropertyType::class, 'property_types_features')
            ->withPivot('is_required_for_type', 'order_for_type')
            ->withTimestamps();
    }

    public function getOptionsCollectionAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->options);
    }

    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public static function getFeaturesBySectionsForPropertyType($propertyTypeId = null)
    {
        $query = static::with(['featureSection', 'propertyTypes']);

        if ($propertyTypeId) {
            $query->whereHas('propertyTypes', function ($q) use ($propertyTypeId) {
                $q->where('property_types.id', $propertyTypeId);
            });
        }

        return $query->get()
            ->groupBy('featureSection.name')
            ->map(function ($features, $sectionName) use ($propertyTypeId) {
                return [
                    'section_name' => $sectionName,
                    'section_id' => $features->first()->featureSection->id ?? null,
                    'features' => $features->map(function ($feature) use ($propertyTypeId) {
                        $pivotData = null;
                        if ($propertyTypeId) {
                            $pivotData = $feature->propertyTypes
                                ->where('id', $propertyTypeId)
                                ->first()
                                ?->pivot;
                        }

                        return [
                            'id' => $feature->id,
                            'name' => $feature->name,
                            'description' => $feature->description,
                            'input_type' => $feature->input_type,
                            'data_type' => $feature->data_type,
                            'is_required' => $pivotData?->is_required_for_type ?? false,
                            'order' => $pivotData?->order_for_type ?? $feature->order,
                        ];
                    })->sortBy('order')->values()
                ];
            });
    }

    public static function getAllSectionsWithFeatures()
    {
        return static::with(['features' => function ($query) {
            $query->orderBy('order');
        }])
            ->active()
            ->ordered()
            ->get()
            ->map(function ($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                    'slug' => $section->slug,
                    'features' => $section->features->map(function ($feature) {
                        return [
                            'id' => $feature->id,
                            'name' => $feature->name,
                            'description' => $feature->description,
                        ];
                    })
                ];
            });
    }
}
