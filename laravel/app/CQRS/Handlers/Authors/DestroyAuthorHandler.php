<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Commands\Authors\DestroyAuthorCommand;
use App\Exceptions\ApiException;
use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class DestroyAuthorHandler
{
    /**
     * @param  AuthorRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    /**
     * @throws ApiException
     */
    public function handle(DestroyAuthorCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
