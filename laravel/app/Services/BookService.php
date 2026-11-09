<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\Books\CreateBookCommand;
use App\CQRS\Commands\Books\DestroyBookCommand;
use App\CQRS\Commands\Books\UpdateBookCommand;
use App\CQRS\Queries\Books\GetBooksPaginateQuery;
use App\CQRS\Queries\Books\GetBooksQuery;
use App\CQRS\Queries\Books\GetByIdBookQuery;
use App\DTOs\DtoInterface;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class BookService implements ServiceInterface
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(): Collection
    {
        return $this->bus->dispatch(new GetBooksQuery);
    }

    public function getListPaginate(): LengthAwarePaginator
    {
        return $this->bus->dispatch(new GetBooksPaginateQuery);
    }

    public function getById(int $id): Book
    {
        return $this->bus->dispatch(new GetByIdBookQuery($id));
    }

    public function create(DtoInterface $dto): Book
    {
        $command = new CreateBookCommand($dto);

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, DtoInterface $dto): Book
    {
        $command = new UpdateBookCommand($id, $dto);

        return $this->bus->dispatch($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->dispatch(new DestroyBookCommand($id));
    }

    public function splitAuthorFullName(string $authorFullName): array
    {
        $authorFullName = trim($authorFullName);

        if (str_contains($authorFullName, ',')) {
            return [$authorFullName, null];
        }

        $authorSplit = explode(' ', $authorFullName);

        if (\count($authorSplit) > 1) {
            $firstNameSplit = \array_slice($authorSplit, 0, -1);

            return [
                trim(implode(' ', $firstNameSplit)),
                trim(end($authorSplit)),
            ];
        }

        return [$authorFullName, null];
    }
}
