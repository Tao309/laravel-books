<?php

namespace App\Services\Messengers\Telegram;

use App\Contracts\MessengerInterface;
use Illuminate\Support\Facades\Http;

class TelegramService implements MessengerInterface
{
    private string $apiUrl;

    public function __construct()
    {
        $token = config('services.telegram.token');

        $this->apiUrl = "https://api.telegram.org/bot{$token}";
    }

    public function sendMessage(
        string $recipientId,
        string $text,
        array $options = [],
    ): array {
        return $this->request('sendMessage', [
            'chat_id' => $recipientId,
            'text' => $text,
            ...$options,
        ]);
    }

    public function editMessage(
        string $recipientId,
        string $messageId,
        string $text,
        array $options = [],
    ): array {
        return $this->request('editMessageText', [
            'chat_id' => $recipientId,
            'message_id' => $messageId,
            'text' => $text,
            ...$options,
        ]);
    }

    public function answerInteraction(
        string $interactionId,
        ?string $text = null,
        bool $alert = false,
    ): void {
        $this->request('answerCallbackQuery', [
            'callback_query_id' => $interactionId,
            'text' => $text,
            'show_alert' => $alert,
        ]);
    }

    /**
     * Универсальный запрос к Telegram API.
     */
    private function request(
        string $method,
        array $params = [],
    ): array {
        $response = Http::post(
            "{$this->apiUrl}/{$method}",
            $params,
        );

        $response->throw();

        $result = $response->json();

        if (! ($result['ok'] ?? false)) {
            throw new \RuntimeException(
                $result['description'] ?? 'Telegram API error.'
            );
        }

        return $result;
    }
}
