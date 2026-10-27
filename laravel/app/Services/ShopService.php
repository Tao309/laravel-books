<?php

namespace App\Services;

use App\Handlers\Shops\GetShopByIdHandler;
use App\Handlers\Shops\GetShopsHandler;
use App\Queries\Shops\GetShopByIdQuery;
use App\Queries\Shops\GetShopsQuery;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

readonly class ShopService
{
    public function __construct(
        private GetShopByIdHandler $getShopByIdHandler,
        private GetShopsHandler $getShopsHandler,
    ) {}

    public function getById(int $id): JsonResource
    {
        return $this->getShopByIdHandler->handle(new GetShopByIdQuery($id))->toResource();
    }

    public function getList(): Collection
    {
        return $this->getShopsHandler->handle(new GetShopsQuery);
    }
}
