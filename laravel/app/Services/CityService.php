<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\Cities\CreateCityCommand;
use App\CQRS\Commands\Cities\DestroyCityCommand;
use App\CQRS\Commands\Cities\UpdateCityCommand;
use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\DTOs\CityDTO;
use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

readonly class CityService
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(): Collection
    {
        return $this->bus->dispatch(new GetCitiesQuery);
    }

    public function create(array $data): City
    {
        $command = new CreateCityCommand(CityDTO::fromArray($data));

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, array $data): City
    {
        $command = new UpdateCityCommand($id, CityDTO::fromArray($data));

        return $this->bus->dispatch($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->dispatch(new DestroyCityCommand($id));
    }
}
