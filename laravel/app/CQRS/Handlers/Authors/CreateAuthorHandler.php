<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Commands\Authors\CreateAuthorCommand;
use App\Exceptions\ApiException;
use App\Models\Author;
use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class CreateAuthorHandler
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
    public function handle(CreateAuthorCommand $command): Author
    {
        return $this->repository->create([
            Author::FIRST_NAME => $command->firstName,
            Author::LAST_NAME => $command->lastName,
        ]);
    }
}
