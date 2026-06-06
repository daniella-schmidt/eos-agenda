<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 
    'email', 
    'password', 
    'timezone', 
    'createdAt', 
    'updatedAt'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    protected $attributes = [
        'timezone' => 'BRT',
    ];

    protected static function booted(): void
    {
        static::creating(function (User $user): void {
            $now = now();

            $user->createdAt ??= $now;
            $user->updatedAt ??= $now;
        });

        static::updating(function (User $user): void {
            $user->updatedAt = now();
        });
    }

    public function getEmailForVerification(): string
    {
        return $this->email;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }
}
