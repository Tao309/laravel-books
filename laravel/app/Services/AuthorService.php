<?php

namespace App\Services;

use App\Commands\Authors\CreateAuthorCommand;
use App\Commands\Authors\DestroyAuthorCommand;
use App\Commands\Authors\UpdateAuthorCommand;
use App\Handlers\Authors\CreateAuthorHandler;
use App\Handlers\Authors\DestroyAuthorHandler;
use App\Handlers\Authors\GetAuthorsHandler;
use App\Handlers\Authors\GetByIdAuthorHandler;
use App\Handlers\Authors\UpdateAuthorHandler;
use App\Models\Author;
use App\Queries\Authors\GetAuthorsQuery;
use App\Queries\Authors\GetByIdAuthorQuery;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class AuthorService
{
    public function __construct(
        private CreateAuthorHandler $createAuthorHandler,
        private UpdateAuthorHandler $updateAuthorHandler,
        private DestroyAuthorHandler $destroyAuthorHandler,
        private GetAuthorsHandler $getAuthorsHandler,
        private GetByIdAuthorHandler $getByIdAuthorHandler,
    ) {}

    public function getById(int $id): JsonResource
    {
        return $this->getByIdAuthorHandler->handle(new GetByIdAuthorQuery($id))->toResource();
    }

    public function getListPaginate(): LengthAwarePaginator
    {
        return $this->getAuthorsHandler->handle(new GetAuthorsQuery);
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
