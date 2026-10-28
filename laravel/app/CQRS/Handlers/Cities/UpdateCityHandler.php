<?php

namespace App\CQRS\Handlers\Cities;

use App\CQRS\Commands\Cities\UpdateCityCommand;
use App\Exceptions\ApiException;
use App\Models\City;
use App\Repositories\CityRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class UpdateCityHandler
{
    /**
     * @param  CityRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    /**
     * @throws ApiException
     */
    public function handle(UpdateCityCommand $command): City
    {
        return $this->repository->update($command->id, $command->dto->toArray());
    }
}
