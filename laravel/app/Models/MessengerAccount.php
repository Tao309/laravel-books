<?php

namespace App\Models;

use App\Enums\MessengerPlatform;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessengerAccount extends Model
{
    protected $fillable = [
        'user_id',
        'platform',
        'external_id',
        'username',
        'first_name',
        'linked_at',
    ];

    protected function casts(): array
    {
        return [
            'platform' => MessengerPlatform::class,
            'linked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function apiToken(): BelongsTo
    {
        return $this->belongsTo(ApiToken::class);
    }
}
