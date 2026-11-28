<?php

namespace App\Console\Commands\ApiToken;

use App\Models\User;
use Illuminate\Console\Command;

class ListApiTokensCommand extends Command
{
    protected $signature = 'app:token-list
                            {username : Имя пользователя}';

    protected $description = 'Показать API-токены пользователя';

    public function handle(): int
    {
        $userName = $this->argument('username');

        $user = User::query()
            ->where('name', $userName)
            ->first();

        if (! $user) {
            $this->error("Пользователь '{$userName}' не найден.");

            return self::FAILURE;
        }

        $tokens = $user->apiTokens()
            ->orderByDesc('created_at')
            ->get();

        if ($tokens->isEmpty()) {
            $this->warn('У пользователя нет API-токенов.');

            return self::SUCCESS;
        }

        $rows = $tokens->map(function ($token) {
            $status = match (true) {
                $token->revoked_at !== null => 'Отозван',
                $token->expires_at !== null && $token->expires_at->isPast() => 'Истёк',
                default => 'Активен',
            };

            return [
                $token->id,
                $token->name ?? '-',
                $token->platform->value,
                $token->created_at?->format('d.m.Y H:i:s') ?? '-',
                $token->expires_at?->format('d.m.Y H:i:s') ?? '-',
                $token->revoked_at?->format('d.m.Y H:i:s') ?? '-',
                $status,
            ];
        });

        $this->table(
            [
                'ID',
                'Название',
                'Платформа',
                'Создан',
                'Истекает',
                'Отозван',
                'Статус',
            ],
            $rows,
        );

        return self::SUCCESS;
    }
}
