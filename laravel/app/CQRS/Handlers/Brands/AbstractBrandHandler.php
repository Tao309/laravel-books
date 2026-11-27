<?php

namespace App\CQRS\Handlers\Brands;

use App\Repositories\BrandRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class AbstractBrandHandler
{
    /**
     * @param  BrandRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
