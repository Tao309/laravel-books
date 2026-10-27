<?php

namespace App\Handlers\Authors;

use App\Models\Author;
use App\Queries\Authors\GetByIdAuthorQuery;
use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class GetByIdAuthorHandler
{
    /**
     * @param  AuthorRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    public function handle(GetByIdAuthorQuery $query): Author
    {
        return $this->repository->get($query->id);
    }
}
