<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Commands\PaperTypes\CreatePaperTypeCommand;
use App\Exceptions\ApiException;
use App\Models\PaperType;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\PaperTypeRepository;

class CreatePaperTypeHandler extends AbstractPaperTypeHandler
{
    /**
     * @param  PaperTypeRepository  $repository
     */
    public function __construct(
        public RepositoryInterface $repository,
    ) {}

    /**
     * @throws ApiException
     */
    public function handle(CreatePaperTypeCommand $command): PaperType
    {
        return $this->repository->create($command->dto->toArray());
    }
}
