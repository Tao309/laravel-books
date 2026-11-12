<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Products\MassSaveProductRequest;
use App\Http\Response\ResponseContext;
use App\Services\MassSaveProductsService;
use App\Services\ServiceInterface;
use Illuminate\Http\JsonResponse;

class MassSaveProductController extends ApiController
{
    public function __construct(
        private readonly MassSaveProductsService $massSaveService,
        ResponseContext $context,
        ?string $resourceClass,
        ?ServiceInterface $service = null,
    ) {
        parent::__construct($context, $service, $resourceClass);
    }

    public function __invoke(MassSaveProductRequest $request): JsonResponse
    {
        $result = $this->massSaveService->process($request->validated()['items']);

        $message = 'Products saved';

        if (! empty($result['error_messages'])) {
            $message = implode('. ', array_unique($result['error_messages']));
            $this->context->setSuccess(false);
        }

        unset($result['error_messages']);

        $this->context->setMessage($message);

        return $this->toJson($result);
    }
}
