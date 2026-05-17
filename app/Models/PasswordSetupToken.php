<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordSetupToken extends Model
{
    protected $fillable = ['user_id', 'token', 'expires_at', 'used_at'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return !$this->used_at && $this->expires_at->isFuture();
    }

    public function getSetupUrl(): string
    {
        return route('password.setup', ['token' => $this->token]);
    }

    public static function createForUser(User $user): self
    {
        return static::create([
            'user_id' => $user->id,
            'token' => Str::random(64),
            'expires_at' => now()->addHours(48),
        ]);
    }
}
