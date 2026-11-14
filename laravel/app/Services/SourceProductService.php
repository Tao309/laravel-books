<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\SourceProducts\CreateSourceProductCommand;
use App\CQRS\Commands\SourceProducts\DestroySourceProductCommand;
use App\CQRS\Commands\SourceProducts\UpdateSourceProductCommand;
use App\CQRS\Queries\SourceProducts\GetSourceProductByIdSQuery;
use App\CQRS\Queries\SourceProducts\GetSourceProductsPaginateQuery;
use App\CQRS\Queries\SourceProducts\GetSourceProductsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\SourceProduct;
use App\Services\Interfaces\GetListPaginateServiceInterface;
use App\Services\Interfaces\GetListServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class SourceProductService implements GetListPaginateServiceInterface, GetListServiceInterface, ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetSourceProductsQuery($dto));
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): LengthAwarePaginator
    {
        return $this->bus->handle(new GetSourceProductsPaginateQuery($dto));
    }

    public function getById(int $id): SourceProduct
    {
        return $this->bus->handle(new GetSourceProductByIdSQuery($id));
    }

    public function create(DtoInterface $dto): SourceProduct
    {
        $command = new CreateSourceProductCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): SourceProduct
    {
        $command = new UpdateSourceProductCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroySourceProductCommand($id));
    }
}
