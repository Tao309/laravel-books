<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\Authors\CreateAuthorCommand;
use App\CQRS\Commands\Authors\DestroyAuthorCommand;
use App\CQRS\Commands\Authors\UpdateAuthorCommand;
use App\CQRS\Queries\Authors\GetAuthorByIdQuery;
use App\CQRS\Queries\Authors\GetAuthorsQuery;
use App\DTOs\AuthorDTO;
use App\Models\Author;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class AuthorService
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getListPaginate(): LengthAwarePaginator
    {
        return $this->bus->dispatch(new GetAuthorsQuery);
    }

    public function getById(int $id): Author
    {
        return $this->bus->dispatch(new GetAuthorByIdQuery($id));
    }

    public function create(array $data): Author
    {
        $command = new CreateAuthorCommand(AuthorDTO::fromArray($data));

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, array $data): Author
    {
        $command = new UpdateAuthorCommand($id, AuthorDTO::fromArray($data));

        return $this->bus->dispatch($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->dispatch(new DestroyAuthorCommand($id));
    }
}
