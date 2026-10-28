<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Queries\Shops\GetShopByIdQuery;
use App\CQRS\Queries\Shops\GetShopsQuery;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;

readonly class ShopService
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
}
