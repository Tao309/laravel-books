<?php

namespace App\CQRS\Handlers\SourceProducts;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\SourceProductRepository;

abstract class AbstractSourceProductHandler
{
    /**
     * @param  SourceProductRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
