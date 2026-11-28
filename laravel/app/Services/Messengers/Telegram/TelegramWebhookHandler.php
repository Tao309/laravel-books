<?php

namespace App\Services\Messengers\Telegram;

use App\Enums\MessengerPlatform;
use App\Services\Messengers\MessengerCommandHandler;

class TelegramWebhookHandler
{
    public function __construct(
        private readonly TelegramService $telegram,
        private readonly MessengerCommandHandler $commandHandler,
    ) {}

    // Основной обработчик Telegram Update.
    public function handle(array $update): void
    {
        if (isset($update['message'])) {
            $this->handleMessage($update['message']);

            return;
        }

        if (isset($update['callback_query'])) {
            $this->handleCallback($update['callback_query']);
        }
    }

    // Обработка обычных сообщений.
    private function handleMessage(array $message): void
    {
        if (($message['chat']['type'] ?? null) !== 'private') {
            return;
        }

        $telegramId = (string) $message['from']['id'];
        $chatId = (string) $message['chat']['id'];
        $text = trim($message['text'] ?? '');

        if ($text === '/start') {
            $result = $this->commandHandler->start(
                platform: MessengerPlatform::Telegram,
                externalId: $telegramId,
            );
        } else {
            $result = $this->commandHandler->message(
                platform: MessengerPlatform::Telegram,
                externalId: $telegramId,
                text: $text,
            );
        }

        $this->sendResult($chatId, $result);
    }

    // Обработка нажатий Inline Keyboard.
    private function handleCallback(array $callback): void
    {
        if (($callback['message']['chat']['type'] ?? null) !== 'private') {
            return;
        }

        // Сразу подтверждаем нажатие.
        $this->telegram->answerInteraction(
            interactionId: $callback['id'],
        );

        $telegramId = (string) $callback['from']['id'];

        $chatId = (string) $callback['message']['chat']['id'];

        $messageId = (string) $callback['message']['message_id'];

        $action = $callback['data'] ?? '';

        $result = $this->commandHandler->callback(
            platform: MessengerPlatform::Telegram,
            externalId: $telegramId,
            action: $action,
        );

        if ($result['edit'] ?? false) {
            $this->editResult(
                $chatId,
                $messageId,
                $result,
            );

            return;
        }

        $this->sendResult($chatId, $result);
    }

    // Отправить результат обработки.
    private function sendResult(
        string $chatId,
        array $result,
    ): void {
        $options = [];

        if ($keyboard = $this->getKeyboard($result['keyboard'] ?? null)) {
            $options['reply_markup'] = $keyboard;
        }

        $this->telegram->sendMessage(
            recipientId: $chatId,
            text: $result['text'],
            options: $options,
        );
    }

    // Редактировать существующее сообщение.
    private function editResult(
        string $chatId,
        string $messageId,
        array $result,
    ): void {
        $options = [];

        if ($keyboard = $this->getKeyboard($result['keyboard'] ?? null)) {
            $options['reply_markup'] = $keyboard;
        } else {
            $options['reply_markup'] = [
                'inline_keyboard' => [],
            ];
        }

        $this->telegram->editMessage(
            recipientId: $chatId,
            messageId: $messageId,
            text: $result['text'],
            options: $options,
        );
    }

    // Получить клавиатуру по её идентификатору.
    private function getKeyboard(?string $name): ?array
    {
        return match ($name) {
            'main' => TelegramKeyboard::main(),
            'settings' => TelegramKeyboard::settings(),
            'back' => TelegramKeyboard::back(),
            default => null,
        };
    }
}
