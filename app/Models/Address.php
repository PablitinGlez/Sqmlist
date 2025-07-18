<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     * 'additional_references' ha sido eliminado.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'street',
        'outdoor_number',
        'interior_number',
        'no_external_number', // ¡Añade este!
        'no_interior_number', // ¡Añade este!
        'postal_code',
        'state_name',
        'municipality_name',
        'neighborhood_name',
        'latitude',
        'longitude',
        'google_place_id',
        'google_address_components',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'google_address_components' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'no_external_number' => 'boolean', // ¡Añade este!
        'no_interior_number' => 'boolean',  // ¡Añade este!
    ];

    /**
     * The "booted" method of the model.
     * Este método se ejecuta una vez cuando el modelo es cargado.
     * Aquí registramos los "observadores" de eventos.
     */
    protected static function boot()
    {
        parent::boot();

        // Cuando una dirección es CREADA o ACTUALIZADA,
        // le decimos a la propiedad asociada que regenere su slug.
        static::saved(function (Address $address) {
            // Asegurarse de que la propiedad existe y que la relación está cargada
            // antes de intentar regenerar el slug.
            if ($address->property) {
                $address->property->regenerateSlug(); // <-- Llamará al nuevo método en Property
            }
        });
    }

    // --- Relaciones ---

    /**
     * Una dirección pertenece a una propiedad.
     * (Relación uno a uno)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // --- Accessors / Mutators ---

    /**
     * Obtiene la dirección completa formateada.
     *
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        $addressParts = [];

        // Añadir calle y números
        if ($this->street) {
            $streetPart = $this->street;
            if ($this->outdoor_number && !$this->no_external_number) {
                $streetPart .= " #{$this->outdoor_number}";
            } elseif ($this->no_external_number) {
                $streetPart .= " S/N"; // Si no hay número exterior y se marca como "sin número"
            }
            if ($this->interior_number && !$this->no_interior_number) {
                $streetPart .= ", Int. {$this->interior_number}";
            } elseif ($this->no_interior_number) {
                $streetPart .= ", Int. S/N"; // Si no hay número interior y se marca como "sin número"
            }
            $addressParts[] = $streetPart;
        }

        // Añadir colonia, código postal, municipio y estado
        if ($this->neighborhood_name) {
            $neighborhoodPart = "Col. {$this->neighborhood_name}";
            if ($this->postal_code) {
                $neighborhoodPart .= " C.P. {$this->postal_code}";
            }
            $addressParts[] = $neighborhoodPart;
        } elseif ($this->postal_code) { // Si no hay colonia pero sí CP
            $addressParts[] = "C.P. {$this->postal_code}";
        }

        if ($this->municipality_name) {
            $addressParts[] = $this->municipality_name;
        }
        if ($this->state_name) {
            $addressParts[] = $this->state_name;
        }

        // Filtrar partes vacías y unirlas con comas
        return implode(', ', array_filter($addressParts));
    }
}
