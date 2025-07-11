<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $token
 * @property mixed $user_id
 * @property mixed $id
 * @property mixed $is_active
 * @property mixed $expires_at
 * @method static create(array $array)
 */
class Link extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the link.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the history records for the link.
     */
    public function history(): HasMany
    {
        return $this->hasMany(History::class);
    }

    /**
     * Check if the link is accessible.
     */
    public function isAccessible(): bool
    {
        return $this->is_active && !$this->expires_at->isPast();
    }

    /**
     * Get the access error message if a link is not accessible.
     */
    public function getAccessError(): ?string
    {
        if (!$this->is_active) {
            return 'Ссылка деактивирована';
        }
        
        if ($this->expires_at->isPast()) {
            return 'Срок действия ссылки истек';
        }
        
        return null;
    }
}
