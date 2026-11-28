<?php

namespace App\Services;

use App\Enums\MessengerPlatform;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

    public function create(User $user, MessengerPlatform $platform, ?string $name = null): array
    {
        $token = bin2hex(random_bytes(32));

        $apiToken = DB::transaction(function () use ($user, $platform, $name, $token) {
            $tokens = $user->apiTokens()
                ->where('platform', $platform->value)
                ->whereNull('revoked_at')
                ->get();

            foreach ($tokens as $apiToken) {
                $apiToken->revoked_at = now();
                $apiToken->save();
            }

            $apiToken = new ApiToken([
                'platform' => $platform,
                'name' => $name,
            ]);

            $apiToken->token_hash = $this->hash($token);
            $apiToken->token = $token;
            $apiToken->expires_at = now()->addMonth();

            $user->apiTokens()->save($apiToken);

            return $apiToken;
        });

        return [
            'model' => $apiToken,
            'token' => $token,
        ];
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
