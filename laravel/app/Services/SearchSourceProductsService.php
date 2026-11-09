<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Queries\SourceProducts\GetSourceProductsQuery;

readonly class SearchSourceProductsService
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function searchByValue(string $value)
    {
        return $this->bus->dispatch(new GetSourceProductsQuery(search: $value));
    }
}
