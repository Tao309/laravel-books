<?php

namespace App\Handlers\Cities;

use App\Queries\Cities\GetCitiesQuery;
use App\Repositories\CityRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetCitiesHandler
{
    /**
     * @param  CityRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    public function handle(GetCitiesQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
