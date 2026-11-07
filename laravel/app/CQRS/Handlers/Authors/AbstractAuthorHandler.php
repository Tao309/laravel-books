<?php

namespace App\CQRS\Handlers\Authors;

use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class AbstractAuthorHandler
{
    /**
     * @param  AuthorRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}
}
