<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Commands\BindingTypes\CreateBindingTypeCommand;
use App\CQRS\Commands\BindingTypes\DestroyBindingTypeCommand;
use App\CQRS\Commands\BindingTypes\UpdateBindingTypeCommand;
use App\CQRS\Queries\BindingTypes\GetBindingTypesQuery;
use App\DTOs\BindingTyperDTO;
use App\Models\BindingType;
use Illuminate\Database\Eloquent\Collection;

readonly class BindingTypeService
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function getList(): Collection
    {
        return $this->bus->dispatch(new GetBindingTypesQuery);
    }

    public function create(array $data): BindingType
    {
        $command = new CreateBindingTypeCommand(BindingTyperDTO::fromArray($data));

        return $this->bus->dispatch($command);
    }

    public function updateById(int $id, array $data): BindingType
    {
        $command = new UpdateBindingTypeCommand($id, BindingTyperDTO::fromArray($data));

        return $this->bus->dispatch($command);
    }

    public function deleteById(int $id): bool
    {
        return $this->bus->dispatch(new DestroyBindingTypeCommand($id));
    }
}
