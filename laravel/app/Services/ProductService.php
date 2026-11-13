<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\Products\CreateProductCommand;
use App\CQRS\Commands\Products\DestroyProductCommand;
use App\CQRS\Commands\Products\UpdateProductCommand;
use App\CQRS\Queries\Products\GetProductByIdQuery;
use App\CQRS\Queries\Products\GetProductsPaginateQuery;
use App\CQRS\Queries\Products\GetProductsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\Product;
use App\Services\Interfaces\GetListPaginateServiceInterface;
use App\Services\Interfaces\GetListServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class ProductService implements GetListPaginateServiceInterface, GetListServiceInterface, ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetProductsQuery($dto));
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): LengthAwarePaginator
    {
        return $this->bus->handle(new GetProductsPaginateQuery($dto));
    }

    public function getById(int $id): Product
    {
        return $this->bus->handle(new GetProductByIdQuery($id));
    }

    public function create(DtoInterface $dto): Product
    {
        $command = new CreateProductCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): Product
    {
        $command = new UpdateProductCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroyProductCommand($id));
    }
}
