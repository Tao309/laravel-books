<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\PublishingHouses\CreatePublishingHouseCommand;
use App\CQRS\Commands\PublishingHouses\DestroyPublishingHouseCommand;
use App\CQRS\Commands\PublishingHouses\UpdatePublishingHouseCommand;
use App\CQRS\Queries\PublishingHouses\GetPublishingHouseByIdQuery;
use App\CQRS\Queries\PublishingHouses\GetPublishingHousesQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\PublishingHouse;
use App\Services\Interfaces\GetListServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;

readonly class PublishingHouseService implements GetListServiceInterface, ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetPublishingHousesQuery($dto));
    }

    public function getById(int $id): PublishingHouse
    {
        return $this->bus->handle(new GetPublishingHouseByIdQuery($id));
    }

    public function create(DtoInterface $dto): PublishingHouse
    {
        $command = new CreatePublishingHouseCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): PublishingHouse
    {
        $command = new UpdatePublishingHouseCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroyPublishingHouseCommand($id));
    }
}
