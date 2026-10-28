<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\PublishingHouses\CreatePublishingHouseCommand;
use App\CQRS\Commands\PublishingHouses\UpdatePublishingHouseCommand;
use App\CQRS\Queries\PublishingHouses\GetPublishingHouseByIdQuery;
use App\CQRS\Queries\PublishingHouses\GetPublishingHousesQuery;
use App\DTOs\PublishingHouseDTO;
use App\Models\PublishingHouse;
use Illuminate\Database\Eloquent\Collection;

readonly class PublishingHouseService
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(): Collection
    {
        return $this->bus->dispatch(new GetPublishingHousesQuery);
    }

    public function getById(int $id): PublishingHouse
    {
        return $this->bus->dispatch(new GetPublishingHouseByIdQuery($id));
    }

    public function create(array $data): PublishingHouse
    {
        $command = new CreatePublishingHouseCommand(PublishingHouseDTO::fromArray($data));

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, array $data): PublishingHouse
    {
        $command = new UpdatePublishingHouseCommand($id, PublishingHouseDTO::fromArray($data));

        return $this->bus->dispatch($command);
    }
}
