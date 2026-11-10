<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\Products\CreateProductCommand;
use App\CQRS\Commands\Products\DestroyProductCommand;
use App\CQRS\Commands\Products\UpdateProductCommand;
use App\CQRS\Queries\Products\GetByIdProductQuery;
use App\CQRS\Queries\Products\GetProductsPaginateQuery;
use App\CQRS\Queries\Products\GetProductsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ProductService implements ServiceInterface
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->dispatch(new GetProductsQuery($dto));
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): LengthAwarePaginator
    {
        return $this->bus->dispatch(new GetProductsPaginateQuery($dto));
    }

    public function getById(int $id): Product
    {
        return $this->bus->dispatch(new GetByIdProductQuery($id));
    }

    public function create(DtoInterface $dto): Product
    {
        $command = new CreateProductCommand($dto);

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, DtoInterface $dto): Product
    {
        $command = new UpdateProductCommand($id, $dto);

        return $this->bus->dispatch($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->dispatch(new DestroyProductCommand($id));
    }
}
