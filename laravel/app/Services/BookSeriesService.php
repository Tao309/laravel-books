<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\BookSeries\CreateBookSeriesCommand;
use App\CQRS\Commands\BookSeries\DestroyBookSeriesCommand;
use App\CQRS\Commands\BookSeries\UpdateBookSeriesCommand;
use App\CQRS\Queries\BookSeries\GetBookSeriesByIdQuery;
use App\CQRS\Queries\BookSeries\GetBookSeriesQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\BookSeries;
use App\Services\Interfaces\GetListServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;

readonly class BookSeriesService implements GetListServiceInterface, ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetBookSeriesQuery($dto));
    }

    public function getById(int $id): BookSeries
    {
        return $this->bus->handle(new GetBookSeriesByIdQuery($id));
    }

    public function create(DtoInterface $dto): BookSeries
    {
        $command = new CreateBookSeriesCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): BookSeries
    {
        $command = new UpdateBookSeriesCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroyBookSeriesCommand($id));
    }
}
