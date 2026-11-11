<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Products\MassSaveProductRequest;
use App\Http\Response\ResponseContext;
use App\Services\MassSaveProductsService;
use App\Services\ServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

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

    #[OA\Post(
        path: '/api/products/mass-save',
        description: 'Mass Save Products',
        summary: 'Mass Save Products',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/MassSaveProductRequest'
            )
        ),

        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Mass Save Products',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/ProductDataResource'
                )
            ),
        ]
    )]
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
