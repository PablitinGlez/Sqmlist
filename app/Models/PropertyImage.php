<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PropertyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'path',
        'alt_text',
        'order',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function getFullUrlAttribute(): string
    {
        if (filter_var($this->path, FILTER_VALIDATE_URL)) {
            return $this->path;
        }

        return asset('storage/' . $this->path);
    }

    public function fileExists(): bool
    {
        if (filter_var($this->path, FILTER_VALIDATE_URL)) {
            return true;
        }

        return Storage::disk('public')->exists($this->path);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    protected static function booted()
    {
        static::deleting(function ($image) {
            if (!filter_var($image->path, FILTER_VALIDATE_URL)) {
                if (Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
            }
        });
    }
}
