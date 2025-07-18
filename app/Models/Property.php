<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Property extends Model
{
    use HasFactory, SoftDeletes, Notifiable, HasSlug;

    // --- Constantes de Estado de Propiedad ---
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_SOLD = 'sold';
    public const STATUS_RENTED = 'rented';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'property_type_id',
        'title',
        'slug',
        'description',
        'price',
        'operation_type',
        'status',
        'draft_expires_at',
        'published_at',
        'approved_at',
        'rejected_at',
        'admin_notes',
        'contact_whatsapp_number',
        'contact_phone_number',
        'contact_email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'draft_expires_at' => 'datetime',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the options for generating the slug.
     * Este método es requerido por el trait HasSlug de Spatie.
     */
    public function getSlugOptions(): SlugOptions
    {
        // Asegurarse de que la relación 'address' esté cargada si no lo está.
        $this->loadMissing('address', 'propertyType');

        // Traducir el tipo de operación a español para el slug
        $operationName = match ($this->operation_type) {
            'sale' => 'venta',
            'rent' => 'renta',
            'both' => 'venta-y-renta',
            default => 'operacion'
        };

        // Obtener el nombre del tipo de propiedad
        $propertyTypeName = $this->propertyType->name ?? 'propiedad';

        // Recolectar las partes de la dirección
        $addressParts = [];
        if ($this->address) {
            if (!empty($this->address->neighborhood_name)) {
                $addressParts[] = $this->address->neighborhood_name;
            }
            if (!empty($this->address->municipality_name)) {
                $addressParts[] = $this->address->municipality_name;
            }
            if (!empty($this->address->state_name)) {
                $addressParts[] = $this->address->state_name;
            }
        }

        // Combinar todas las partes para formar la cadena base del slug
        $slugComponents = array_filter([
            $propertyTypeName,
            'en',
            $operationName,
            $this->title,
            ...$addressParts
        ]);

        // Unir todas las partes con guiones
        $fullSlugString = implode('-', $slugComponents);

        return SlugOptions::create()
            ->generateSlugsFrom(fn($property) => $fullSlugString)
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate(false);
    }

    /**
     * Forzar la regeneración del slug de la propiedad.
     * Este método es llamado desde el modelo Address cuando la dirección es guardada/actualizada.
     */
    public function regenerateSlug(): void
    {
        // Cargar las relaciones necesarias
        $this->loadMissing('address', 'propertyType');

        // Generar nuevo slug y guardarlo
        $this->slug = $this->generateSlug();
        $this->save();
    }

    /**
     * Método auxiliar para generar el slug manualmente
     */
    private function generateSlug(): string
    {
        // Asegurar que las relaciones estén cargadas
        $this->loadMissing('address', 'propertyType');

        // Traducir el tipo de operación a español para el slug
        $operationName = match ($this->operation_type) {
            'sale' => 'venta',
            'rent' => 'renta',
            'both' => 'venta-y-renta',
            default => 'operacion'
        };

        // Obtener el nombre del tipo de propiedad
        $propertyTypeName = $this->propertyType->name ?? 'propiedad';

        // Recolectar las partes de la dirección
        $addressParts = [];
        if ($this->address) {
            if (!empty($this->address->neighborhood_name)) {
                $addressParts[] = $this->address->neighborhood_name;
            }
            if (!empty($this->address->municipality_name)) {
                $addressParts[] = $this->address->municipality_name;
            }
            if (!empty($this->address->state_name)) {
                $addressParts[] = $this->address->state_name;
            }
        }

        // Combinar todas las partes
        $slugComponents = array_filter([
            $propertyTypeName,
            'en',
            $operationName,
            $this->title,
            ...$addressParts
        ]);

        // Crear el slug base
        $baseSlug = implode('-', $slugComponents);

        // Convertir a slug válido (minúsculas, sin acentos, solo guiones y letras)
        $slug = \Illuminate\Support\Str::slug($baseSlug);

        // Asegurar que sea único
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // --- Relaciones ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function featuredImage(): HasOne
    {
        return $this->hasOne(PropertyImage::class)->where('is_featured', true);
    }

    public function featureValues(): HasMany
    {
        return $this->hasMany(PropertyFeatureValue::class);
    }

    // --- Scopes ---

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublishedForSale($query)
    {
        return $query->where('status', 'published')->where('operation_type', 'sale');
    }

    public function scopePublishedForRent($query)
    {
        return $query->where('status', 'published')->where('operation_type', 'rent');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhereHas('address', fn($q) => $q->where('street', 'like', "%{$search}%")
                ->orWhere('neighborhood_name', 'like', "%{$search}%")
                ->orWhere('municipality_name', 'like', "%{$search}%")
                ->orWhere('state_name', 'like', "%{$search}%")
                ->orWhere('postal_code', 'like', "%{$search}%"));
    }

    // --- Métodos auxiliares ---

    public function getFeatureValue(string $featureSlug)
    {
        if (!$this->relationLoaded('featureValues')) {
            $this->load('featureValues.feature');
        }

        $featureValue = $this->featureValues->first(function ($fv) use ($featureSlug) {
            return $fv->feature && $fv->feature->slug === $featureSlug;
        });

        if (!$featureValue) {
            return null;
        }

        return $featureValue->casted_value;
    }

    public function updateImages(array $imagePaths): void
    {
        $this->images()->delete();
        foreach ($imagePaths as $index => $path) {
            $this->images()->create([
                'path' => $path,
                'order' => $index + 1,
                'is_featured' => $index === 0,
            ]);
        }
    }
}
