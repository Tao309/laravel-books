<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\PublishingBrands\CreatePublishingBrandCommand;
use App\CQRS\Commands\PublishingBrands\UpdatePublishingBrandCommand;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandByIdQuery;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandsQuery;
use App\DTOs\DtoInterface;
use App\Models\PublishingBrand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class PublishingBrandService implements ServiceInterface
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(): Collection
    {
        return $this->bus->dispatch(new GetPublishingBrandsQuery);
    }

    public function getById(int $id): PublishingBrand
    {
        return $this->bus->dispatch(new GetPublishingBrandByIdQuery($id));
    }

    public function create(DtoInterface $dto): PublishingBrand
    {
        return $this->bus->dispatch(new CreatePublishingBrandCommand($dto));
    }

    public function updateById(int $id, DtoInterface $dto): PublishingBrand
    {
        $command = new UpdatePublishingBrandCommand($id, $dto);

        return $this->bus->dispatch($command);
    }

    public function getListPaginate(): ?LengthAwarePaginator
    {
        return null;
    }
}
