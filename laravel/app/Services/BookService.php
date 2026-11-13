<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\Books\CreateBookCommand;
use App\CQRS\Commands\Books\DestroyBookCommand;
use App\CQRS\Commands\Books\UpdateBookCommand;
use App\CQRS\Queries\Books\GetBookByIdQuery;
use App\CQRS\Queries\Books\GetBooksPaginateQuery;
use App\CQRS\Queries\Books\GetBooksQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\Book;
use App\Services\Interfaces\GetListPaginateServiceInterface;
use App\Services\Interfaces\GetListServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class BookService implements GetListPaginateServiceInterface, GetListServiceInterface, ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetBooksQuery($dto));
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): LengthAwarePaginator
    {
        return $this->bus->handle(new GetBooksPaginateQuery($dto));
    }

    public function getById(int $id): Book
    {
        return $this->bus->handle(new GetBookByIdQuery($id));
    }

    public function create(DtoInterface $dto): Book
    {
        $command = new CreateBookCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): Book
    {
        $command = new UpdateBookCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroyBookCommand($id));
    }
}
