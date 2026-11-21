<?php

namespace App\Http\Response;

class ResponseContext
{
    public const string MESSAGE = 'message';

    public const string SUCCESS = 'success';

    public const string DATA = 'data';

    public const string ITEMS = 'items';

    public function __construct(
        private bool $success = false,
        private ?string $message = null,
    ) {}

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): static
    {
        $this->success = $success;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
