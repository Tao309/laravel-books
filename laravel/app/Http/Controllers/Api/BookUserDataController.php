<?php

namespace App\Http\Controllers\Api;

use App\Http\Response\ResponseContext;
use App\Repositories\BookUserDataRepository;
use App\Services\Interfaces\ServiceInterface;
use Illuminate\Http\JsonResponse;

class BookUserDataController extends ApiController
{
    public function __construct(
        private readonly BookUserDataRepository $bookUserDataRepository,
        ResponseContext $context,
        ?string $resourceClass,
        ?ServiceInterface $service = null,
    ) {
        parent::__construct($context, $service, $resourceClass);
    }

    public function show(int $bookId): JsonResponse
    {
        return $this->toJson($this->bookUserDataRepository->get($bookId)->toResource());
    }
}
