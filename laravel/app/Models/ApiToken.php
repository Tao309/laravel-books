<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiToken extends Model
{
    public const string TABLE_NAME = 'api_tokens';

    protected $fillable = [
        'user_id',
        'name',
        'token_hash',
        'token',
        'last_used_at',
        'expires_at',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'token' => 'encrypted',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messengerAccounts(): HasMany
    {
        return $this->hasMany(MessengerAccount::class);
    }
}
