<?php

namespace App\Contracts;

interface MessengerInterface
{
    // Отправить сообщение пользователю.
    public function sendMessage(
        string $recipientId,
        string $text,
        array $options = []
    ): array;

    // Редактировать существующее сообщение.
    public function editMessage(
        string $recipientId,
        string $messageId,
        string $text,
        array $options = []
    ): array;

    // Подтвердить взаимодействие пользователя.
    public function answerInteraction(
        string $interactionId,
        ?string $text = null,
        bool $alert = false
    ): void;
}
