<?php

namespace App\Models;

use App\Enums\SmartRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'userId',
    'rawText',
    'intent',
    'extractedTitle',
    'extractedDescription',
    'extractedStartAt',
    'extractedEndAt',
    'extractedParticipants',
    'extractedData',
    'status',
    'errorMessage',
    'createdAt',
    'updatedAt',
])]
class SmartRequest extends Model
{
    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    protected $attributes = [
        'extractedParticipants' => '[]',
        'extractedData' => '[]',
        'status' => SmartRequestStatus::Pending->value,
        'errorMessage' => null,
    ];

    protected static function booted(): void
    {
        static::creating(function (SmartRequest $smartRequest): void {
            $now = now();

            $smartRequest->createdAt ??= $now;
            $smartRequest->updatedAt ??= $now;
        });

        static::updating(function (SmartRequest $smartRequest): void {
            $smartRequest->updatedAt = now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
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
            'extractedStartAt' => 'datetime',
            'extractedEndAt' => 'datetime',
            'extractedParticipants' => 'array',
            'extractedData' => 'array',
            'status' => SmartRequestStatus::class,
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }
}
