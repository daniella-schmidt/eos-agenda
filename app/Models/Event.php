<?php

namespace App\Models;

use App\Enums\EventPriority;
use App\Enums\EventSource;
use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'userId',
    'calendarId',
    'smartRequestId',
    'title',
    'description',
    'startAt',
    'endAt',
    'timezone',
    'location',
    'meetingURL',
    'status',
    'priority',
    'source',
    'isAllDay',
    'isRecurring',
    'createByAI',
    'createdAt',
    'updatedAt',
])]
class Event extends Model
{
    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    protected $attributes = [
        'description' => null,
        'status' => EventStatus::Draft->value,
        'priority' => EventPriority::Medium->value,
        'source' => EventSource::Manual->value,
        'isAllDay' => false,
        'isRecurring' => false,
        'createByAI' => false,
    ];

    protected static function booted(): void
    {
        static::creating(function (Event $event): void {
            $now = now();

            $event->createdAt ??= $now;
            $event->updatedAt ??= $now;
        });

        static::updating(function (Event $event): void {
            $event->updatedAt = now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function calendar(): BelongsTo
    {
        return $this->belongsTo(Calendar::class, 'calendarId');
    }

    public function smartRequest(): BelongsTo
    {
        return $this->belongsTo(SmartRequest::class, 'smartRequestId');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(EventParticipant::class, 'eventId');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(EventReminder::class, 'eventId');
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
            'calendarId' => 'integer',
            'smartRequestId' => 'integer',
            'startAt' => 'datetime',
            'endAt' => 'datetime',
            'status' => EventStatus::class,
            'priority' => EventPriority::class,
            'source' => EventSource::class,
            'isAllDay' => 'boolean',
            'isRecurring' => 'boolean',
            'createByAI' => 'boolean',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }
}
