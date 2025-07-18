<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // ¡Añadida esta línea!
use Illuminate\Database\Eloquent\Relations\HasMany; // ¡Añadida esta línea!


class PropertyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug', // Asegúrate de que 'slug' esté en el fillable si lo tienes en la migración.
        'description',
        'category_id',
        'is_active',
       
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // --- Relaciones ---

    /**
     * Un tipo de propiedad pertenece a una categoría.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Un tipo de propiedad puede tener muchas propiedades.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Un tipo de propiedad puede tener muchas características asociadas.
     * Esta es la relación muchos a muchos con la tabla pivote 'property_types_features'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'property_types_features')
                    ->withPivot('is_required_for_type', 'order_for_type') // Incluye los campos extra de la tabla pivote
                    ->withTimestamps() // Para que Eloquent gestione created_at y updated_at en la tabla pivote
                    ->orderBy('order_for_type'); // Ordena por el campo de orden en la tabla pivote
    }

    // --- Scopes ---

    /**
     * Scope a query to search property types by name, description, or related category name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"));
    }

    /**
     * Scope a query to only include active property types.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include property types by a specific category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // --- Accessors ---

    /**
     * Get the full name of the property type including its category.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        // Asegúrate de que la relación category esté cargada para evitar errores.
        return ($this->category ? "{$this->category->name} - " : '') . $this->name;
    }
}