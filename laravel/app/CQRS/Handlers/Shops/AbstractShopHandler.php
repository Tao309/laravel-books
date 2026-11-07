<?php

namespace App\CQRS\Handlers\Shops;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\ShopRepository;

abstract class AbstractShopHandler
{
    /**
     * @param  ShopRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
