<?php

namespace App\CQRS\Handlers\BindingTypes;

use App\Repositories\BindingTypeRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class AbstractBindingTypeHandler
{
    /**
     * @param  BindingTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
