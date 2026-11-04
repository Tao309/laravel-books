<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\SourceProductTypes\CreateSourceProductTypeCommand;
use App\CQRS\Commands\SourceProductTypes\DestroySourceProductTypeCommand;
use App\CQRS\Commands\SourceProductTypes\UpdateSourceProductTypeCommand;
use App\CQRS\Queries\SourceProductTypes\GetByIdSourceProductTypeQuery;
use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypesQuery;
use App\DTOs\DtoInterface;
use App\Models\SourceProductType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class SourceProductTypeService implements ServiceInterface
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(): Collection
    {
        return $this->bus->dispatch(new GetSourceProductTypesQuery);
    }

    public function getListPaginate(): LengthAwarePaginator
    {
        return $this->bus->dispatch(new GetSourceProductTypesQuery);
    }

    public function getById(int $id): SourceProductType
    {
        return $this->bus->dispatch(new GetByIdSourceProductTypeQuery($id));
    }

    public function create(DtoInterface $dto): SourceProductType
    {
        $command = new CreateSourceProductTypeCommand($dto);

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, DtoInterface $dto): SourceProductType
    {
        $command = new UpdateSourceProductTypeCommand($id, $dto);

        return $this->bus->dispatch($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->dispatch(new DestroySourceProductTypeCommand($id));
    }
}
