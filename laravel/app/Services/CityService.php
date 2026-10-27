<?php

namespace App\Services;

use App\Commands\Cities\CreateCityCommand;
use App\Commands\Cities\DestroyCityCommand;
use App\Commands\Cities\UpdateCityCommand;
use App\Handlers\Cities\CreateCityHandler;
use App\Handlers\Cities\DestroyCityHandler;
use App\Handlers\Cities\GetCitiesHandler;
use App\Handlers\Cities\UpdateCityHandler;
use App\Models\City;
use App\Queries\Cities\GetCitiesQuery;
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
