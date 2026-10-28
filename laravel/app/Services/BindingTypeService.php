<?php

namespace App\Services;

use App\CQRS\Commands\BindingTypes\CreateBindingTypeCommand;
use App\CQRS\Commands\BindingTypes\DestroyBindingTypeCommand;
use App\CQRS\Commands\BindingTypes\UpdateBindingTypeCommand;
use App\CQRS\Handlers\BindingTypes\CreateBindingTypeHandler;
use App\CQRS\Handlers\BindingTypes\DestroyBindingTypeHandler;
use App\CQRS\Handlers\BindingTypes\GetBindingTypesHandler;
use App\CQRS\Handlers\BindingTypes\UpdateBindingTypeHandler;
use App\CQRS\Queries\BindingTypes\GetBindingTypesQuery;
use App\Models\BindingType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

readonly class BindingTypeService
{
    public function __construct(
        private DestroyBindingTypeHandler $destroyBindingTypeHandler,
        private UpdateBindingTypeHandler $updateBindingTypeHandler,
        private CreateBindingTypeHandler $createBindingTypeHandler,
        private GetBindingTypesHandler $getBindingTypesHandler,
    ) {}

    public function getList(): Collection
    {
        return $this->getBindingTypesHandler->handle(new GetBindingTypesQuery);
    }

    public function create(array $validatedData): JsonResource
    {
        $command = new CreateBindingTypeCommand(
            $validatedData[BindingType::LABEL],
        );

        return $this->createBindingTypeHandler->handle($command)->toResource();
    }

    public function updateById(int $id, array $validatedData): JsonResource
    {
        $command = new UpdateBindingTypeCommand(
            $id,
            $validatedData[BindingType::LABEL]
        );

        return $this->updateBindingTypeHandler->handle($command)->toResource();
    }

    public function deleteById(int $id): bool
    {
        return $this->destroyBindingTypeHandler->handle(new DestroyBindingTypeCommand($id));
    }
}
