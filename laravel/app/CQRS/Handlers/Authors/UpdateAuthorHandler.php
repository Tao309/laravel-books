<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Commands\Authors\UpdateAuthorCommand;
use App\Exceptions\ApiException;
use App\Models\Author;
use App\Repositories\AuthorRepository;
use App\Repositories\Interfaces\RepositoryInterface;

class UpdateAuthorHandler
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
    public function handle(UpdateAuthorCommand $command): Author
    {
        return $this->repository->update($command->id, [
            Author::FIRST_NAME => $command->firstName,
            Author::LAST_NAME => $command->lastName,
        ]);
    }
}
