<?php

namespace App\Handlers\Cities;

use App\Commands\Cities\CreateCityCommand;
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
        return $this->repository->create([
            City::CODE => $command->code,
            City::TITLE => $command->title,
        ]);
    }
}
