<?php

namespace App\Http\Controllers\Api;

use App\Http\Response\ResponseContext;
use App\Repositories\SourceProductUserDataRepository;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Http\JsonResponse;

class SourceProductUserDataController extends ApiController
{
    public function __construct(
        private readonly SourceProductUserDataRepository $repository,
        ResponseContext $context,
        ?string $resourceClass,
        ?ServiceInterface $service = null,
    ) {
        parent::__construct($context, $service, $resourceClass);
    }

    public function show(int $sourceProductId): JsonResponse
    {
        return $this->toJson($this->repository->get($sourceProductId)->toResource());
    }
}
