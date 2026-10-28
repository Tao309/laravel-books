<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\Authors\CreateAuthorCommand;
use App\CQRS\Commands\Authors\DestroyAuthorCommand;
use App\CQRS\Commands\Authors\UpdateAuthorCommand;
use App\CQRS\Queries\Authors\GetAuthorByIdQuery;
use App\CQRS\Queries\Authors\GetAuthorsQuery;
use App\DTOs\DtoInterface;
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

    public function create(DtoInterface $dto): Author
    {
        $command = new CreateAuthorCommand($dto);

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, DtoInterface $dto): Author
    {
        $command = new UpdateAuthorCommand($id, $dto);

        return $this->bus->dispatch($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->dispatch(new DestroyAuthorCommand($id));
    }
}
