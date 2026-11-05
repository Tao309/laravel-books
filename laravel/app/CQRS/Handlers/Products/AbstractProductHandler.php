<?php

namespace App\CQRS\Handlers\Products;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\ProductRepository;

abstract class AbstractProductHandler
{
    /**
     * @param  ProductRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
