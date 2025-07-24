<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'street',
        'outdoor_number',
        'interior_number',
        'no_external_number',
        'no_interior_number',
        'postal_code',
        'state_name',
        'municipality_name',
        'neighborhood_name',
        'latitude',
        'longitude',
        'google_place_id',
        'google_address_components',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'google_address_components' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'no_external_number' => 'boolean',
        'no_interior_number' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (Address $address) {
            if ($address->property) {
                $address->property->regenerateSlug();
            }
        });
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function getFullAddressAttribute(): string
    {
        $addressParts = [];

        if ($this->street) {
            $streetPart = $this->street;
            if ($this->outdoor_number && !$this->no_external_number) {
                $streetPart .= " #{$this->outdoor_number}";
            } elseif ($this->no_external_number) {
                $streetPart .= " S/N";
            }
            if ($this->interior_number && !$this->no_interior_number) {
                $streetPart .= ", Int. {$this->interior_number}";
            } elseif ($this->no_interior_number) {
                $streetPart .= ", Int. S/N";
            }
            $addressParts[] = $streetPart;
        }

        if ($this->neighborhood_name) {
            $neighborhoodPart = "Col. {$this->neighborhood_name}";
            if ($this->postal_code) {
                $neighborhoodPart .= " C.P. {$this->postal_code}";
            }
            $addressParts[] = $neighborhoodPart;
        } elseif ($this->postal_code) {
            $addressParts[] = "C.P. {$this->postal_code}";
        }

        if ($this->municipality_name) {
            $addressParts[] = $this->municipality_name;
        }
        if ($this->state_name) {
            $addressParts[] = $this->state_name;
        }

        return implode(', ', array_filter($addressParts));
    }
}
