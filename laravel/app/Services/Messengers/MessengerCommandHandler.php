<?php

namespace App\Services\Messengers;

use App\Enums\MessengerPlatform;
use App\Models\MessengerAccount;
use App\Services\MessengerAuthService;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

class MessengerCommandHandler
{
    public function __construct(
        private readonly MessengerAuthService $auth,
    ) {}

    // Обработка команды /start.
    public function start(
        MessengerPlatform $platform,
        string $externalId,
    ): array {
        $account = $this->getAccount($platform, $externalId);

        if (! $account) {
            $this->setState(
                $platform,
                $externalId,
                'awaiting_token',
            );

            return [
                'text' => 'Для начала работы введите ваш API Token.',
                'keyboard' => null,
            ];
        }

        return [
            'text' => 'Добро пожаловать! Выберите действие:',
            'keyboard' => 'main',
        ];
    }

    // Обработка обычного сообщения.
    public function message(
        MessengerPlatform $platform,
        string $externalId,
        string $text,
    ): array {
        if (str_starts_with($text, '/')) {
            return [
                'text' => 'Доступные команды: /start, /help',
                'keyboard' => null,
            ];
        }

        $state = $this->getState($platform, $externalId);

        // Ожидаем пользовательский API-токен.
        if ($state === 'awaiting_token') {
            return $this->authorize(
                $platform,
                $externalId,
                $text,
            );
        }

        $account = $this->getAccount($platform, $externalId);

        if (! $account) {
            return $this->start($platform, $externalId);
        }

        return match ($text) {
            '/help' => [
                'text' => 'Доступные команды: /start, /help, /logout',
                'keyboard' => 'main',
            ],
            default => [
                'text' => 'Неизвестная команда. Используйте /help.',
                'keyboard' => 'main',
            ],
        };
    }

    // Авторизация по пользовательскому API-токену.
    private function authorize(
        MessengerPlatform $platform,
        string $externalId,
        string $token,
    ): array {
        try {
            $this->auth->authorize(
                platform: $platform,
                externalId: $externalId,
                inputToken: trim($token),
            );

            $this->forgetState($platform, $externalId);

            return [
                'text' => 'Авторизация прошла успешно!',
                'keyboard' => 'main',
            ];
        } catch (RuntimeException $exception) {
            return [
                'text' => 'Не удалось авторизоваться. Проверьте токен или обратитесь к администратору.',
                'keyboard' => null,
            ];
        }
    }

    // Обработка нажатия кнопки.
    public function callback(
        MessengerPlatform $platform,
        string $externalId,
        string $action,
    ): array {
        $account = $this->getAccount($platform, $externalId);

        if (! $account) {
            return [
                'text' => 'Необходимо авторизоваться.',
                'keyboard' => null,
                'edit' => true,
            ];
        }

        return match ($action) {
            'main' => [
                'text' => 'Выберите действие:',
                'keyboard' => 'main',
                'edit' => true,
            ],

            'profile' => [
                'text' => 'Информация о вашем профиле.',
                'keyboard' => 'back',
                'edit' => true,
            ],

            'products' => [
                'text' => 'Ваши товары.',
                'keyboard' => 'back',
                'edit' => true,
            ],

            'statistics' => [
                'text' => 'Ваша статистика.',
                'keyboard' => 'back',
                'edit' => true,
            ],

            'settings' => [
                'text' => 'Настройки аккаунта.',
                'keyboard' => 'settings',
                'edit' => true,
            ],

            default => [
                'text' => 'Неизвестное действие.',
                'keyboard' => null,
                'edit' => false,
            ],
        };
    }

    // Получить привязанный аккаунт.
    private function getAccount(
        MessengerPlatform $platform,
        string $externalId,
    ): ?MessengerAccount {
        return MessengerAccount::query()
            ->where('platform', $platform)
            ->where('external_id', $externalId)
            ->first();
    }

    // Управление временным состоянием пользователя.
    private function setState(
        MessengerPlatform $platform,
        string $externalId,
        string $state,
    ): void {
        Cache::put(
            $this->stateKey($platform, $externalId),
            $state,
            now()->addMinutes(10),
        );
    }

    private function getState(
        MessengerPlatform $platform,
        string $externalId,
    ): ?string {
        return Cache::get(
            $this->stateKey($platform, $externalId)
        );
    }

    private function forgetState(
        MessengerPlatform $platform,
        string $externalId,
    ): void {
        Cache::forget(
            $this->stateKey($platform, $externalId)
        );
    }

    private function stateKey(
        MessengerPlatform $platform,
        string $externalId,
    ): string {
        return "messenger:{$platform->value}:{$externalId}";
    }
}
