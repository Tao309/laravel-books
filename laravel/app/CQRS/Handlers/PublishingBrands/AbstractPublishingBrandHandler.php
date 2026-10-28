<?php

namespace App\CQRS\Handlers\PublishingBrands;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PublishingBrandRepository;

abstract class AbstractPublishingBrandHandler
{
    /**
     * @param  PublishingBrandRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
