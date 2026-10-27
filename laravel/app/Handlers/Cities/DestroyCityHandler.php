<?php

namespace App\Handlers\Cities;

use App\Commands\Cities\DestroyCityCommand;
use App\Exceptions\ApiException;
use App\Repositories\CityRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class DestroyCityHandler
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
    public function handle(DestroyCityCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
