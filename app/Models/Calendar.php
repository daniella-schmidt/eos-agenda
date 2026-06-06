<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'userId',
    'name',
    'description',
    'color',
    'isDefault',
    'isActive'
])]
class Calendar extends Model
{
    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $attributes = [
        'description' => null,
        'color' => null,
        'isDefault' => false,
        'isActive' => true,
    ];

    protected function casts(): array
    {
        return [
            'userId' => 'integer',
            'isDefault' => 'boolean',
            'isActive' => 'boolean',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'calendarId');
    }
}
