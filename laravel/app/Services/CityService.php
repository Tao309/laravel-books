<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\Cities\CreateCityCommand;
use App\CQRS\Commands\Cities\DestroyCityCommand;
use App\CQRS\Commands\Cities\UpdateCityCommand;
use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\DTOs\DtoInterface;
use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class CityService implements ServiceInterface
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(): Collection
    {
        return $this->bus->dispatch(new GetCitiesQuery);
    }

    public function create(DtoInterface $dto): City
    {
        $command = new CreateCityCommand($dto);

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, DtoInterface $dto): City
    {
        $command = new UpdateCityCommand($id, $dto);

        return $this->bus->dispatch($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->dispatch(new DestroyCityCommand($id));
    }

    public function getListPaginate(): ?LengthAwarePaginator
    {
        return null;
    }

    public function getById(int $id): ?Model
    {
        return null;
    }
}
