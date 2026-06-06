<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'userId',
    'defaultEventDurationMinutes',
    'defaultMeetingDurationMinutes',
    'preferredStartTime',
    'preferredEndTime',
    'bufferBetweenEventsMinutes',
    'requireConfirmationBeforeCreate',
    'autoCreateMeetingLink',
    'autoCreateReminder',
    'createdAt',
    'updatedAt',
])]
class UserPreference extends Model
{
    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    protected static function booted(): void
    {
        static::creating(function (UserPreference $userPreference): void {
            $now = now();

            $userPreference->createdAt ??= $now;
            $userPreference->updatedAt ??= $now;
        });

        static::updating(function (UserPreference $userPreference): void {
            $userPreference->updatedAt = now();
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
            'defaultEventDurationMinutes' => 'integer',
            'defaultMeetingDurationMinutes' => 'integer',
            'bufferBetweenEventsMinutes' => 'integer',
            'requireConfirmationBeforeCreate' => 'boolean',
            'autoCreateMeetingLink' => 'boolean',
            'autoCreateReminder' => 'boolean',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }
}
