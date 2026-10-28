<?php

namespace App\CQRS\Handlers\Cities;

use App\CQRS\Commands\Cities\CreateCityCommand;
use App\Exceptions\ApiException;
use App\Models\City;
use App\Repositories\CityRepository;
use App\Repositories\Interfaces\RepositoryInterface;

readonly class CreateCityHandler
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
    public function handle(CreateCityCommand $command): City
    {
        return $this->repository->create($command->dto->toArray());
    }
}
