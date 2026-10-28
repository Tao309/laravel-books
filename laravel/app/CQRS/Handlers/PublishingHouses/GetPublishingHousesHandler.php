<?php

namespace App\CQRS\Handlers\PublishingHouses;

use App\CQRS\Queries\PublishingHouses\GetPublishingHousesQuery;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PublishingHouseRepository;
use Illuminate\Database\Eloquent\Collection;

class GetPublishingHousesHandler
{
    /**
     * @param  PublishingHouseRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    public function handle(GetPublishingHousesQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
