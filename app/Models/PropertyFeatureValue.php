<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PropertyFeatureValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'feature_id',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // --- Relaciones ---

    /**
     * Un valor de característica pertenece a una propiedad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Un valor de característica pertenece a una definición de característica.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    // --- Accessors ---

    /**
     * Obtiene el valor casteado según el tipo de dato definido en la característica.
     * Esto es crucial para trabajar con los valores dinámicos en Filament.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function castedValue(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // Asegurarse de que la relación 'feature' esté cargada
                if (!isset($this->feature)) {
                    $this->load('feature');
                }

                if (!$this->feature || !$this->feature->data_type) {
                    return $attributes['value'];
                }

                $rawValue = $attributes['value'];
                $dataType = $this->feature->data_type;
                $inputType = $this->feature->input_type;

                switch ($dataType) {
                    case 'integer':
                        return is_numeric($rawValue) ? (int) $rawValue : null;

                    case 'float':
                        return is_numeric($rawValue) ? (float) $rawValue : null;

                    case 'boolean':
                        // ✅ CORREGIDO: Manejo simple para checkboxes booleanos
                        if ($inputType === 'checkbox') {
                            // Convertir directamente a boolean
                            // Los valores pueden ser: 1, '1', true, 'true', 0, '0', false, 'false', null
                            if (is_null($rawValue) || $rawValue === '' || $rawValue === '0' || $rawValue === 0 || $rawValue === false) {
                                return false;
                            }
                            return (bool) $rawValue;
                        }
                        // Para otros input_types booleanos
                        return (bool) $rawValue;

                    case 'array':
                        $decodedValue = json_decode($rawValue, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedValue)) {
                            if ($inputType === 'text') {
                                return implode(', ', $decodedValue);
                            }
                            return $decodedValue;
                        }
                        return (string) $rawValue;

                    case 'json':
                        $decodedValue = json_decode($rawValue);
                        if (json_last_error() === JSON_ERROR_NONE && (is_object($decodedValue) || is_array($decodedValue))) {
                            if ($inputType === 'text') {
                                return json_encode($decodedValue);
                            }
                            return $decodedValue;
                        }
                        return (string) $rawValue;

                    case 'string':
                    default:
                        return (string) $rawValue;
                }
            },
            set: fn($value) => $value,
        );
    }
}
