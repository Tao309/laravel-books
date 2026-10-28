<?php

namespace App\CQRS\Handlers\PublishingHouses;

use App\CQRS\Commands\PublishingHouses\CreatePublishingHouseCommand;
use App\Exceptions\ApiException;
use App\Models\PublishingHouse;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PublishingHouseRepository;

class CreatePublishingHouseHandler
{
    /**
     * @param  PublishingHouseRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    /**
     * @throws ApiException
     */
    public function handle(CreatePublishingHouseCommand $command): PublishingHouse
    {
        return $this->repository->create($command->dto->toArray());
    }
}
