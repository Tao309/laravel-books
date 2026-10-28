<?php

namespace App\Services;

use App\CQRS\Commands\Authors\CreateAuthorCommand;
use App\CQRS\Commands\Authors\DestroyAuthorCommand;
use App\CQRS\Commands\Authors\UpdateAuthorCommand;
use App\CQRS\Handlers\Authors\CreateAuthorHandler;
use App\CQRS\Handlers\Authors\DestroyAuthorHandler;
use App\CQRS\Handlers\Authors\GetAuthorByIdHandler;
use App\CQRS\Handlers\Authors\GetAuthorsHandler;
use App\CQRS\Handlers\Authors\UpdateAuthorHandler;
use App\CQRS\Queries\Authors\GetAuthorByIdQuery;
use App\CQRS\Queries\Authors\GetAuthorsQuery;
use App\Models\Author;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class AuthorService
{
    public function __construct(
        private CreateAuthorHandler $createAuthorHandler,
        private UpdateAuthorHandler $updateAuthorHandler,
        private DestroyAuthorHandler $destroyAuthorHandler,
        private GetAuthorByIdHandler $getAuthorByIdHandler,
        private GetAuthorsHandler $getAuthorsHandler,
    ) {}

    public function getListPaginate(): LengthAwarePaginator
    {
        return $this->getAuthorsHandler->handle(new GetAuthorsQuery);
    }

    public function getById(int $id): JsonResource
    {
        return $this->getAuthorByIdHandler->handle(new GetAuthorByIdQuery($id))->toResource();
    }

    public function create(array $validatedData): JsonResource
    {
        $command = new CreateAuthorCommand(
            $validatedData[Author::FIRST_NAME],
            $validatedData[Author::LAST_NAME]
        );

        return $this->createAuthorHandler->handle($command)->toResource();
    }

    public function updateById(int $id, array $validatedData): JsonResource
    {
        $command = new UpdateAuthorCommand(
            $id,
            $validatedData[Author::FIRST_NAME],
            $validatedData[Author::LAST_NAME]
        );

        return $this->updateAuthorHandler->handle($command)->toResource();
    }

    public function deleteById(int $id): bool
    {
        return $this->destroyAuthorHandler->handle(new DestroyAuthorCommand($id));
    }
}
