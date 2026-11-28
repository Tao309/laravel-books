<?php

namespace App\Services\Messengers\Telegram;

class TelegramKeyboard
{
    // Главное меню.
    public static function main(): array
    {
        return [
            'inline_keyboard' => [
                [
                    [
                        'text' => '👤 Мой профиль',
                        'callback_data' => 'profile',
                    ],
                    [
                        'text' => '📦 Мои товары',
                        'callback_data' => 'products',
                    ],
                ],
                [
                    [
                        'text' => '📊 Статистика',
                        'callback_data' => 'statistics',
                    ],
                    [
                        'text' => '⚙️ Настройки',
                        'callback_data' => 'settings',
                    ],
                ],
            ],
        ];
    }

    // Меню настроек.
    public static function settings(): array
    {
        return [
            'inline_keyboard' => [
                [
                    [
                        'text' => '🔐 Отвязать аккаунт',
                        'callback_data' => 'logout',
                    ],
                ],
                [
                    [
                        'text' => '⬅️ Назад',
                        'callback_data' => 'main',
                    ],
                ],
            ],
        ];
    }

    // Кнопка возврата.
    public static function back(): array
    {
        return [
            'inline_keyboard' => [
                [
                    [
                        'text' => '⬅️ Назад',
                        'callback_data' => 'main',
                    ],
                ],
            ],
        ];
    }

    // Клавиатура подтверждения.
    public static function confirm(
        string $action,
    ): array {
        return [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Да',
                        'callback_data' => "{$action}:confirm",
                    ],
                    [
                        'text' => 'Нет',
                        'callback_data' => "{$action}:cancel",
                    ],
                ],
            ],
        ];
    }
}
