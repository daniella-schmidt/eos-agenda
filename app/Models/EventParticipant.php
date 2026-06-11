<?php

namespace App\Models;

use App\Enums\EventParticipantResponseStatus;
use App\Enums\EventParticipantRole;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'eventId',
    'contactId',
    'name',
    'email',
    'role',
    'responseStatus',
    'createAt',
])]
class EventParticipant extends Model
{
    public const CREATED_AT = 'createAt';

    public const UPDATED_AT = null;

    protected $attributes = [
        'responseStatus' => EventParticipantResponseStatus::Pending->value,
    ];

    protected static function booted(): void
    {
        static::creating(function (EventParticipant $eventParticipant): void {
            $eventParticipant->createAt ??= now();
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'eventId');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contactId');
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
            'contactId' => 'integer',
            'role' => EventParticipantRole::class,
            'responseStatus' => EventParticipantResponseStatus::class,
            'createAt' => 'datetime',
        ];
    }
}
