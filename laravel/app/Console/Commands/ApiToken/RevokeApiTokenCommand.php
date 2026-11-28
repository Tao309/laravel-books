<?php

namespace App\Console\Commands\ApiToken;

use App\Enums\MessengerPlatform;
use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;

class RevokeApiTokenCommand extends Command
{
    protected $signature = 'app:token-revoke
                            {username : Имя пользователя}
                            {platform : telegram, max или vk}';

    protected $description = 'Отозвать API-токен пользователя';

    public function handle(): int
    {
        $userName = $this->argument('username');

        $platform = MessengerPlatform::tryFrom(
            $this->argument('platform')
        );

        if (! $platform) {
            $this->error('Неизвестная платформа. Доступны: telegram, max или vk.');

            return self::FAILURE;
        }

        $user = User::query()
            ->where('name', $userName)
            ->first();

        if (! $user) {
            $this->error("Пользователь '{$userName}' не найден.");

            return self::FAILURE;
        }

        $tokens = $user->apiTokens()
            ->where('platform', $platform->value)
            ->whereNull('revoked_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();

        if ($tokens->isEmpty()) {
            $this->warn("Действующих API-токенов для {$platform->value} не найдено.");

            return self::SUCCESS;
        }

        $this->warn("Найдено токенов: {$tokens->count()}");

        if (! confirm('Отозвать токен(ы)?')) {
            $this->info('Отзыв токенов отменён.');

            return self::SUCCESS;
        }

        foreach ($tokens as $token) {
            $token->revoked_at = now();
            $token->save();
        }

        $this->info("Отозвано токенов: {$tokens->count()}");

        return self::SUCCESS;
    }
}
