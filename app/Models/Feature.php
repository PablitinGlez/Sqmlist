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
}
