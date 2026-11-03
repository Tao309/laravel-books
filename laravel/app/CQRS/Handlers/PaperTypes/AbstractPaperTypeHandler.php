<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PaperTypeRepository;

abstract class AbstractPaperTypeHandler
{
    /**
     * @param  PaperTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
