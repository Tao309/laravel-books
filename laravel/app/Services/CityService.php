<?php

namespace App\Services;

use App\CQRS\Commands\Cities\CreateCityCommand;
use App\CQRS\Commands\Cities\DestroyCityCommand;
use App\CQRS\Commands\Cities\UpdateCityCommand;
use App\CQRS\Handlers\Cities\CreateCityHandler;
use App\CQRS\Handlers\Cities\DestroyCityHandler;
use App\CQRS\Handlers\Cities\GetCitiesHandler;
use App\CQRS\Handlers\Cities\UpdateCityHandler;
use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

readonly class CityService
{
    public function __construct(
        private DestroyCityHandler $destroyCityHandler,
        private UpdateCityHandler $updateCityHandler,
        private CreateCityHandler $createCityHandler,
        private GetCitiesHandler $getCitiesHandler,
    ) {}

    public function getList(): Collection
    {
        return $this->getCitiesHandler->handle(new GetCitiesQuery);
    }

    public function create(array $validatedData): JsonResource
    {
        $command = new CreateCityCommand(
            $validatedData[City::CODE],
            $validatedData[City::TITLE],
        );

        return $this->createCityHandler->handle($command)->toResource();
    }

    public function updateById(int $id, array $validatedData): JsonResource
    {
        $command = new UpdateCityCommand(
            $id,
            $validatedData[City::CODE],
            $validatedData[City::TITLE],
        );

        return $this->updateCityHandler->handle($command)->toResource();
    }

    public function deleteById(int $id): bool
    {
        return $this->destroyCityHandler->handle(new DestroyCityCommand($id));
    }
}
