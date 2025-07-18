<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PropertyImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'path',
        'alt_text',
        'order',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // --- Relaciones ---

    /**
     * Una imagen pertenece a una propiedad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // --- Accessors ---

    /**
     * Obtiene la URL completa de la imagen.
     *
     * @return string
     */
    public function getFullUrlAttribute(): string
    {
        // Si la ruta ya es una URL completa (CDN), devolverla tal como está
        if (filter_var($this->path, FILTER_VALIDATE_URL)) {
            return $this->path;
        }

        // Si es una ruta local, generar la URL usando asset()
        return asset('storage/' . $this->path);
    }

    /**
     * ✅ NUEVO: Verifica si el archivo físico existe
     */
    public function fileExists(): bool
    {
        // Solo verificar archivos locales, no URLs externas
        if (filter_var($this->path, FILTER_VALIDATE_URL)) {
            return true; // Asumimos que URLs externas existen
        }

        return Storage::disk('public')->exists($this->path);
    }

    // --- Scopes ---

    /**
     * ✅ NUEVO: Scope para ordenar por orden
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * ✅ NUEVO: Scope para obtener solo imágenes destacadas
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // --- Eventos del modelo ---

    /**
     * ✅ NUEVO: Eliminar archivo físico cuando se elimina el registro
     */
    protected static function booted()
    {
        static::deleting(function ($image) {
            // Solo eliminar archivos locales, no URLs externas
            if (!filter_var($image->path, FILTER_VALIDATE_URL)) {
                if (Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
            }
        });
    }
}
