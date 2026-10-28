<?php

namespace App\CQRS\Handlers\PublishingHouses;

use App\CQRS\Commands\PublishingHouses\UpdatePublishingHouseCommand;
use App\Exceptions\ApiException;
use App\Models\PublishingHouse;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PublishingHouseRepository;

class UpdatePublishingHouseHandler
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
    public function handle(UpdatePublishingHouseCommand $command): PublishingHouse
    {
        return $this->repository->update($command->id, $command->dto->toArray());
    }
}
