<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\PublishingBrands\CreatePublishingBrandCommand;
use App\CQRS\Commands\PublishingBrands\DestroyPublishingBrandCommand;
use App\CQRS\Commands\PublishingBrands\UpdatePublishingBrandCommand;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandByIdQuery;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandsQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\PublishingBrand;
use App\Services\Interfaces\GetListServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Collection;

readonly class PublishingBrandService implements GetListServiceInterface, ServiceInterface
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

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroyPublishingBrandCommand($id));
    }
}
