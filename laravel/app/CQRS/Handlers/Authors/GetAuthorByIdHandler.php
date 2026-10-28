<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Queries\Authors\GetAuthorByIdQuery;
use App\Models\Author;
use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class GetAuthorByIdHandler
{
    /**
     * @param  AuthorRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    public function handle(GetAuthorByIdQuery $query): Author
    {
        return $this->repository->get($query->id);
    }
}
