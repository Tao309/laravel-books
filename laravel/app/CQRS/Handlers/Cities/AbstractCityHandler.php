<?php

namespace App\CQRS\Handlers\Cities;

use App\Repositories\CityRepository;
use App\Repositories\Interfaces\RepositoryInterface;

abstract class AbstractCityHandler
{
    /**
     * @param  CityRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
