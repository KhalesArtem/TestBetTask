<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class History extends Model
{
    protected $fillable = [
        'link_id',
        'random_number',
        'result',
        'win_amount',
    ];

    protected $casts = [
        'win_amount' => 'decimal:2',
    ];

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }
}
