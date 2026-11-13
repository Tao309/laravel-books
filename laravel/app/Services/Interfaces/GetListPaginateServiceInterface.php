<?php

namespace App\Services\Interfaces;

use App\DTOs\CollectionQueryDtoInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetListPaginateServiceInterface
{
    public function getListPaginate(CollectionQueryDtoInterface $dto): ?LengthAwarePaginator;
}
