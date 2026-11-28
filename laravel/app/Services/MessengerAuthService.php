<?php

namespace App\Services;

use App\Enums\MessengerPlatform;
use App\Models\MessengerAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

readonly class MessengerAuthService
{
    public function __construct(
        private ApiTokenService $apiTokenService,
    ) {}

    public function authorize(
        MessengerPlatform $platform,
        string $externalId,
        string $inputToken,
        array $profile = [],
    ): MessengerAccount {
        $apiToken = $this->apiTokenService->findValid($inputToken);

        if (! $apiToken) {
            throw new RuntimeException('Invalid API token.');
        }

        return DB::transaction(function () use (
            $apiToken,
            $platform,
            $externalId,
            $profile,
        ) {
            $user = User::query()
                ->lockForUpdate()
                ->findOrFail($apiToken->user_id);

            // Проверяем, не занят ли аккаунт другим пользователем.
            $existingAccount = MessengerAccount::query()
                ->where('platform', $platform)
                ->where('external_id', $externalId)
                ->first();

            if ($existingAccount) {
                if ($existingAccount->user_id !== $user->id) {
                    throw new RuntimeException(
                        'Messenger account already linked.'
                    );
                }

                return $existingAccount;
            }

            // Проверяем, не подключён ли другой аккаунт этой платформы.
            $userAccount = MessengerAccount::query()
                ->where('user_id', $user->id)
                ->where('platform', $platform)
                ->first();

            if ($userAccount) {
                throw new RuntimeException(
                    'User already has an account on this platform.'
                );
            }

            $account = MessengerAccount::create([
                'user_id' => $user->id,
                'platform' => $platform,
                'external_id' => $externalId,
                'username' => $profile['username'] ?? null,
                'first_name' => $profile['first_name'] ?? null,
                'linked_at' => now(),
            ]);

            $this->apiTokenService->markAsUsed($apiToken);

            return $account;
        });
    }

    public function logout(MessengerPlatform $platform, string $externalId): bool
    {
        return (bool) MessengerAccount::query()
            ->where('platform', $platform)
            ->where('external_id', $externalId)
            ->delete();
    }
}
