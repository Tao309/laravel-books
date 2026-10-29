<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\BookSeries\CreateBookSeriesCommand;
use App\CQRS\Commands\BookSeries\UpdateBookSeriesCommand;
use App\CQRS\Queries\BookSeries\GetBookSeriesByIdQuery;
use App\CQRS\Queries\BookSeries\GetBookSeriesQuery;
use App\DTOs\DtoInterface;
use App\Models\BookSeries;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class BookSeriesService implements ServiceInterface
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(): Collection
    {
        return $this->bus->dispatch(new GetBookSeriesQuery);
    }

    public function getById(int $id): BookSeries
    {
        return $this->bus->dispatch(new GetBookSeriesByIdQuery($id));
    }

    public function create(DtoInterface $dto): BookSeries
    {
        $command = new CreateBookSeriesCommand($dto);

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, DtoInterface $dto): BookSeries
    {
        $command = new UpdateBookSeriesCommand($id, $dto);

        return $this->bus->dispatch($command);
    }

    public function getListPaginate(): ?LengthAwarePaginator
    {
        return null;
    }
}
