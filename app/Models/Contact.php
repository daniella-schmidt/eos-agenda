<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'userId',
    'name',
    'email',
    'phone',
    'company',
    'notes',
    'createdAt',
    'updatedAt',
])]
class Contact extends Model
{
    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    protected $attributes = [
        'notes' => null,
    ];

    protected static function booted(): void
    {
        static::creating(function (Contact $contact): void {
            $now = now();

            $contact->createdAt ??= $now;
            $contact->updatedAt ??= $now;
        });

        static::updating(function (Contact $contact): void {
            $contact->updatedAt = now();
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
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }
}
