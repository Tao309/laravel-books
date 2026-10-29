<?php

namespace App\CQRS\Handlers\BookSeries;

use App\Repositories\BookSeriesRepository;
use App\Repositories\Interfaces\RepositoryInterface;

abstract class AbstractBookSeriesHandler
{
    /**
     * @param  BookSeriesRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
