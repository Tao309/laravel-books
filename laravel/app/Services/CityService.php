<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\Cities\CreateCityCommand;
use App\CQRS\Commands\Cities\DestroyCityCommand;
use App\CQRS\Commands\Cities\UpdateCityCommand;
use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\CQRS\Queries\Cities\GetCityByIdQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\City;
use App\Services\Interfaces\GetListServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

readonly class CityService implements GetListServiceInterface, ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetCitiesQuery($dto));
    }

    public function create(DtoInterface $dto): City
    {
        $command = new CreateCityCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): City
    {
        $command = new UpdateCityCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroyCityCommand($id));
    }

    public function getById(int $id): ?Model
    {
        return $this->bus->handle(new GetCityByIdQuery($id));
    }
}
