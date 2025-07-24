<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipality extends Model
{
    use HasFactory;

    protected $table = 'municipalities';

    protected $fillable = [
        'state_id',
        'name',
        'clave',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function colonias(): HasMany
    {
        return $this->hasMany(Colonia::class);
    }
}
