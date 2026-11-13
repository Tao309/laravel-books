<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\PublishingBrands\CreatePublishingBrandCommand;
use App\CQRS\Commands\PublishingBrands\UpdatePublishingBrandCommand;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandByIdQuery;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\PublishingBrand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class PublishingBrandService implements ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetPublishingBrandsQuery($dto));
    }

    public function getById(int $id): PublishingBrand
    {
        return $this->bus->handle(new GetPublishingBrandByIdQuery($id));
    }

    public function create(DtoInterface $dto): PublishingBrand
    {
        return $this->bus->handle(new CreatePublishingBrandCommand($dto));
    }

    public function updateById(int $id, DtoInterface $dto): PublishingBrand
    {
        $command = new UpdatePublishingBrandCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): ?LengthAwarePaginator
    {
        return null;
    }
}
