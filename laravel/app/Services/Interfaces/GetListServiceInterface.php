<?php

namespace App\Services\Interfaces;

use App\DTOs\CollectionQueryDtoInterface;
use Illuminate\Database\Eloquent\Collection;

interface GetListServiceInterface
{
    public function getList(CollectionQueryDtoInterface $dto): ?Collection;
}
