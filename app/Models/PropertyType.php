<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
