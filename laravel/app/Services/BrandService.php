<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\Brands\CreateBrandCommand;
use App\CQRS\Commands\Brands\DestroyBrandCommand;
use App\CQRS\Commands\Brands\UpdateBrandCommand;
use App\CQRS\Queries\Brands\GetBrandByIdQuery;
use App\CQRS\Queries\Brands\GetBrandsPaginateQuery;
use App\CQRS\Queries\Brands\GetBrandsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\Brand;
use App\Services\Interfaces\GetListPaginateServiceInterface;
use App\Services\Interfaces\GetListServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class BrandService implements GetListPaginateServiceInterface, GetListServiceInterface, ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetBrandsQuery($dto));
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): LengthAwarePaginator
    {
        return $this->bus->handle(new GetBrandsPaginateQuery($dto));
    }

    public function getById(int $id): Brand
    {
        return $this->bus->handle(new GetBrandByIdQuery($id));
    }

    public function create(DtoInterface $dto): Brand
    {
        $command = new CreateBrandCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): Brand
    {
        $command = new UpdateBrandCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroyBrandCommand($id));
    }
}
