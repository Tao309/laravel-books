<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\SourceProducts\CreateSourceProductCommand;
use App\CQRS\Commands\SourceProducts\DestroySourceProductCommand;
use App\CQRS\Commands\SourceProducts\UpdateSourceProductCommand;
use App\CQRS\Queries\SourceProducts\GetByIdSourceProductQuery;
use App\CQRS\Queries\SourceProducts\GetSourceProductsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\SourceProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class SourceProductService implements ServiceInterface
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->dispatch(new GetSourceProductsQuery($dto));
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): LengthAwarePaginator
    {
        return $this->bus->dispatch(new GetSourceProductsQuery($dto));
    }

    public function getById(int $id): SourceProduct
    {
        return $this->bus->dispatch(new GetByIdSourceProductQuery($id));
    }

    public function create(DtoInterface $dto): SourceProduct
    {
        $command = new CreateSourceProductCommand($dto);

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, DtoInterface $dto): SourceProduct
    {
        $command = new UpdateSourceProductCommand($id, $dto);

        return $this->bus->dispatch($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->dispatch(new DestroySourceProductCommand($id));
    }
}
