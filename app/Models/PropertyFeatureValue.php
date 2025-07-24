<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PropertyFeatureValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'feature_id',
        'value',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    protected function castedValue(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
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
                        if ($inputType === 'checkbox') {
                            if (is_null($rawValue) || $rawValue === '' || $rawValue === '0' || $rawValue === 0 || $rawValue === false) {
                                return false;
                            }
                            return (bool) $rawValue;
                        }
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
