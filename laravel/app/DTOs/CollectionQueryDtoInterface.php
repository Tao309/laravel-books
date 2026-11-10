<?php

namespace App\DTOs;

interface CollectionQueryDtoInterface
{
    public function getSearch(): ?string;

    public function getLimit(): ?int;

    public function getIds(): ?array;

    public function hasFilters(): bool;
}
