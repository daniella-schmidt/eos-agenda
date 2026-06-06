<?php

namespace App\Models;

use App\Enums\EventReminderType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'eventId',
    'type',
    'minutesBefore',
    'isSent',
    'sentAt',
    'createAt',
])]
class EventReminder extends Model
{
    public const CREATED_AT = 'createAt';

    public const UPDATED_AT = null;

    protected $attributes = [
        'type' => EventReminderType::notification->value,
        'isSent' => false,
        'sentAt' => null,
    ];

    protected static function booted(): void
    {
        static::creating(function (EventReminder $eventReminder): void {
            $eventReminder->createAt ??= now();
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'eventId');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'eventId' => 'integer',
            'type' => EventReminderType::class,
            'minutesBefore' => 'integer',
            'isSent' => 'boolean',
            'sentAt' => 'datetime',
            'createAt' => 'datetime',
        ];
    }
}
