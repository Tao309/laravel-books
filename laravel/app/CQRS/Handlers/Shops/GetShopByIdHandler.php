<?php

namespace App\CQRS\Handlers\Shops;

use App\CQRS\Queries\Shops\GetShopByIdQuery;
use App\Models\Shop;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\ShopRepository;

class GetShopByIdHandler
{
    /**
     * @param  ShopRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    public function handle(GetShopByIdQuery $query): Shop
    {
        return $this->repository->get($query->id);
    }
}
