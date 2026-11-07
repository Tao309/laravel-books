<?php

namespace App\CQRS\Handlers\PublishingHouses;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PublishingHouseRepository;

abstract class AbstractPublishingHouseHandler
{
    /**
     * @param  PublishingHouseRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
