<?php

namespace App\CQRS\Handlers\SourceProductTypes;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\SourceProductTypeRepository;

abstract class AbstractSourceProductTypeHandler
{
    /**
     * @param  SourceProductTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
