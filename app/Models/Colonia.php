<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Colonia extends Model
{
    use HasFactory;

    protected $table = 'colonias';

    protected $fillable = [
        'municipality_id',
        'name',
        'postal_code',
        'tipo_asentamiento',
        'zona',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }
}
