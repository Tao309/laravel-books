<?php

namespace App\CQRS\Queries;

abstract class AbstractGetCollectionQuery implements GetCollectionQueryInterface
{
    public const string SEARCH = 'search';

    public const string LIMIT = 'limit';

    public function __construct(
        public ?string $search = null,
        public int $limit = 30,
    ) {}

    public function toArray(): array
    {
        return [
            self::SEARCH => $this->search,
            self::LIMIT => $this->limit,
        ];
    }
}
