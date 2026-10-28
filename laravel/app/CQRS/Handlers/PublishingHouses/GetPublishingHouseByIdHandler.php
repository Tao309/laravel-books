<?php

namespace App\CQRS\Handlers\PublishingHouses;

use App\CQRS\Queries\PublishingHouses\GetPublishingHouseByIdQuery;
use App\Models\PublishingHouse;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PublishingHouseRepository;

class GetPublishingHouseByIdHandler
{
    /**
     * @param  PublishingHouseRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    public function handle(GetPublishingHouseByIdQuery $query): PublishingHouse
    {
        return $this->repository->get($query->id);
    }
}
