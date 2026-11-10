<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\PublishingHouses\CreatePublishingHouseCommand;
use App\CQRS\Commands\PublishingHouses\UpdatePublishingHouseCommand;
use App\CQRS\Queries\PublishingHouses\GetPublishingHouseByIdQuery;
use App\CQRS\Queries\PublishingHouses\GetPublishingHousesQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\PublishingHouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class PublishingHouseService implements ServiceInterface
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->dispatch(new GetPublishingHousesQuery($dto));
    }

    public function getById(int $id): PublishingHouse
    {
        return $this->bus->dispatch(new GetPublishingHouseByIdQuery($id));
    }

    public function create(DtoInterface $dto): PublishingHouse
    {
        $command = new CreatePublishingHouseCommand($dto);

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, DtoInterface $dto): PublishingHouse
    {
        $command = new UpdatePublishingHouseCommand($id, $dto);

        return $this->bus->dispatch($command);
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): ?LengthAwarePaginator
    {
        return null;
    }
}
