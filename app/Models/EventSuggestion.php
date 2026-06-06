<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'userId',
    'smartRequesId',
    'suggested_start_at',
    'suggested_end_at',
    'score',
    'reason',
    'selected',
    'createdAt',
])]
class EventSuggestion extends Model
{
    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = null;

    protected $attributes = [
        'selected' => false,
    ];

    protected static function booted(): void
    {
        static::creating(function (EventSuggestion $eventSuggestion): void {
            $eventSuggestion->createdAt ??= now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function smartRequest(): BelongsTo
    {
        return $this->belongsTo(SmartRequest::class, 'smartRequesId');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'userId' => 'integer',
            'smartRequesId' => 'integer',
            'suggested_start_at' => 'datetime',
            'suggested_end_at' => 'datetime',
            'score' => 'decimal:2',
            'selected' => 'boolean',
            'createdAt' => 'datetime',
        ];
    }
}
