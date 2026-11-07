<?php

namespace App\CQRS\Handlers\Shops;

use App\CQRS\Queries\Shops\GetShopByIdQuery;
use App\Models\Shop;

class GetShopByIdHandler extends AbstractShopHandler
{
    public function handle(GetShopByIdQuery $query): Shop
    {
        return $this->repository->get($query->id);
    }
}
