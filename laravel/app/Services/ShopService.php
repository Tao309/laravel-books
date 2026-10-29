<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Queries\Shops\GetShopByIdQuery;
use App\CQRS\Queries\Shops\GetShopsQuery;
use App\DTOs\DtoInterface;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ShopService implements ServiceInterface
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getById(int $id): Shop
    {
        return $this->bus->dispatch(new GetShopByIdQuery($id));
    }

    public function getList(): Collection
    {
        return $this->bus->dispatch(new GetShopsQuery);
    }

    public function getListPaginate(): ?LengthAwarePaginator
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
