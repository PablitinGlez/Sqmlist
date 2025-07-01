<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'read_at',
        'is_archived',
        'archived_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getSubjectLabelAttribute(): string
    {
        return [
            'consulta_general' => 'Consulta General',
            'soporte_tecnico' => 'Soporte Técnico',
            'ventas' => 'Ventas',
            'otros' => 'Otros'
        ][$this->subject] ?? $this->subject;
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function archive(): void
    {
        $this->update([
            'is_archived' => true,
            'archived_at' => now()
        ]);
    }

    public function unarchive(): void
    {
        $this->update([
            'is_archived' => false,
            'archived_at' => null
        ]);
    }
}
