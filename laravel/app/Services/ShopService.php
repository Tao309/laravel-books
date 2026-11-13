<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Queries\Shops\GetShopByIdQuery;
use App\CQRS\Queries\Shops\GetShopsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ShopService implements ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getById(int $id): Shop
    {
        return $this->bus->handle(new GetShopByIdQuery($id));
    }

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetShopsQuery($dto));
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): ?LengthAwarePaginator
    {
        return null;
    }

    public function create(DtoInterface $dto): ?Model
    {
        return null;
    }

    public function updateById(int $id, DtoInterface $dto): ?Model
    {
        return null;
    }
}
