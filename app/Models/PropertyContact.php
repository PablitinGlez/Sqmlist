<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'sender_name',
        'sender_email',
        'sender_phone',
        'message_text',
        'is_read',
        'read_at',
        'is_archived',
        'archived_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function getSenderFullNameAttribute(): string
    {
        return $this->sender_name;
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

    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function archive(): void
    {
        if (!$this->is_archived) {
            $this->update([
                'is_archived' => true,
                'archived_at' => now(),
            ]);
        }
    }

    public function unarchive(): void
    {
        $this->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);
    }
}
