<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Commands\SourceProductTypes\CreateSourceProductTypeCommand;
use App\CQRS\Commands\SourceProductTypes\DestroySourceProductTypeCommand;
use App\CQRS\Commands\SourceProductTypes\UpdateSourceProductTypeCommand;
use App\CQRS\Queries\SourceProductTypes\GetByIdSourceProductTypeQuery;
use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypesQuery;
use App\DTOs\CollectionQueryDtoInterface;
use App\DTOs\DtoInterface;
use App\Models\SourceProductType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class SourceProductTypeService implements ServiceInterface
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function getList(CollectionQueryDtoInterface $dto): Collection
    {
        return $this->bus->handle(new GetSourceProductTypesQuery($dto));
    }

    public function getListPaginate(CollectionQueryDtoInterface $dto): LengthAwarePaginator
    {
        return $this->bus->handle(new GetSourceProductTypesQuery($dto));
    }

    public function getById(int $id): SourceProductType
    {
        return $this->bus->handle(new GetByIdSourceProductTypeQuery($id));
    }

    public function create(DtoInterface $dto): SourceProductType
    {
        $command = new CreateSourceProductTypeCommand($dto);

        return $this->bus->handle($command);
    }

    public function updateById(int $id, DtoInterface $dto): SourceProductType
    {
        $command = new UpdateSourceProductTypeCommand($id, $dto);

        return $this->bus->handle($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->handle(new DestroySourceProductTypeCommand($id));
    }
}
