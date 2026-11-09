<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Queries\Books\GetBooksQuery;

readonly class SearchBooksService
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function searchByValue(string $value)
    {
        return $this->bus->dispatch(new GetBooksQuery(search: $value));
    }
}
