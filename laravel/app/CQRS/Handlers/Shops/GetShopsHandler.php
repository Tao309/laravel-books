<?php

namespace App\CQRS\Handlers\Shops;

use App\CQRS\Queries\Shops\GetShopsQuery;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\ShopRepository;
use Illuminate\Database\Eloquent\Collection;

class GetShopsHandler
{
    /**
     * @param  ShopRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    public function handle(GetShopsQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
