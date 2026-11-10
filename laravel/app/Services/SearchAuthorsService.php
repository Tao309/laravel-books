<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Queries\Authors\GetAuthorsQuery;

readonly class SearchAuthorsService
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function searchByValue(string $value)
    {
        return $this->bus->dispatch(new GetAuthorsQuery(search: $value));
    }
}
