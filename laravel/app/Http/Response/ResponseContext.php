<?php

namespace App\Http\Response;

class ResponseContext
{
    public const string MESSAGE = 'message';

    public const string SUCCESS = 'success';

    public const string DATA = 'data';

    //    private ?string $message = null;

    public function __construct(
        private bool $success = false,
        private ?string $message = null,
        //        private array $data = [],
        private array $trace = [],
        private array $previousTrace = [],
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

    //    public function getData(): array
    //    {
    //        return $this->data;
    //    }
    //
    //    public function setData(array $data): static
    //    {
    //        $this->data = $data;
    //
    //        return $this;
    //    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function setTrace(?array $trace): static
    {
        $this->trace = $trace;

        return $this;
    }

    public function setPreviousTrace(?array $previousTrace): static
    {
        $this->previousTrace = $previousTrace;

        return $this;
    }

    //    public function toArray($showTrace = false): array
    //    {
    //        $result = [
    //            self::SUCCESS => $this->success,
    //            self::MESSAGE => $this->message,
    //            self::DATA => $this->data,
    //        ];
    //
    //        if ($showTrace) {
    //            $result['trace'] = $this->trace;
    //            $result['previousTrace'] = $this->previousTrace;
    //        }
    //
    //        return $result;
    //    }
}
