<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Queries\Authors\GetAuthorsQuery;
use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAuthorsHandler
{
    /**
     * @param  AuthorRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    public function handle(GetAuthorsQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
