<?php

namespace App\Handlers\BindingTypes;

use App\Commands\BindingTypes\CreateBindingTypeCommand;
use App\Exceptions\ApiException;
use App\Models\BindingType;
use App\Repositories\BindingTypeRepository;
use App\Repositories\Interfaces\RepositoryInterface;

readonly class CreateBindingTypeHandler
{
    /**
     * @param  BindingTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    /**
     * @throws ApiException
     */
    public function handle(CreateBindingTypeCommand $command): BindingType
    {
        return $this->repository->create([
            BindingType::LABEL => $command->label,
        ]);
    }
}
