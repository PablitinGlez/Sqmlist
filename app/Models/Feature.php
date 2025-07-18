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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array', // Castea el JSON de opciones a un array PHP
        'is_filterable' => 'boolean',
        'is_searchable' => 'boolean',
        'is_required' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // --- Relaciones ---

    /**
     * Una característica pertenece a una sección de características.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function featureSection(): BelongsTo
    {
        return $this->belongsTo(FeatureSection::class);
    }

    /**
     * Una característica puede tener muchos valores en diferentes propiedades.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function propertyFeatureValues(): HasMany
    {
        return $this->hasMany(PropertyFeatureValue::class);
    }

    /**
     * Una característica puede estar asociada a muchos tipos de propiedad.
     * Esta es la relación muchos a muchos con la tabla pivote 'property_types_features'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function propertyTypes(): BelongsToMany
    {
        return $this->belongsToMany(PropertyType::class, 'property_types_features')
            ->withPivot('is_required_for_type', 'order_for_type')
            ->withTimestamps();
    }

    // --- Accessors ---

    /**
     * Obtiene las opciones de la característica, si existen, como una colección.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getOptionsCollectionAttribute(): \Illuminate\Support\Collection
    {
        return collect($this->options);
    }

    // --- Scopes ---

    /**
     * Scope a query to only include features that are filterable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    /**
     * Scope a query to order features by their 'order' column.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
