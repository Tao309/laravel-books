<?php

namespace App\Services;

use App\Models\ApiToken;

class ApiTokenService
{
    public function generate(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function hash(string $token): string
    {
        return hash('sha256', $token);
    }

    public function findValid(string $token): ?ApiToken
    {
        return ApiToken::query()
            ->where('token_hash', $this->hash($token))
            ->whereNull('revoked_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();
    }

    public function markAsUsed(ApiToken $token): void
    {
        $token->update([
            'last_used_at' => now(),
        ]);
    }
}
