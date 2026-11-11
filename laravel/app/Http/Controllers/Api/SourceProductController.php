<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\SourceProducts\StoreSourceProductRequest;
use App\Http\Requests\SourceProducts\UpdateSourceProductRequest;
use App\Http\Resources\SourceProducts\SourceProductResource;
use App\Services\SourceProductService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SourceProductController extends ApiController
{
    protected string $resourceClass = SourceProductResource::class;

    public function __construct(
        SourceProductService $service,
    ) {
        $this->service = $service;
    }

    #[OA\Get(
        path: '/api/source_products',
        description: 'Get list of SourceProducts',
        summary: 'Get list of SourceProducts',
        requestBody: new OA\RequestBody,
        tags: ['SourceProducts'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/Ids'),
            new OA\Parameter(ref: '#/components/parameters/Limit'),
            new OA\Parameter(ref: '#/components/parameters/Search'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of SourceProducts',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/SourceProductsDataResourceCollection'
                )
            ),
        ]
    )]
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    #[OA\Post(
        path: '/api/source_products',
        description: 'Create SourceProduct',
        summary: 'Create SourceProduct',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreSourceProductRequest'
            )
        ),
        tags: ['SourceProducts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created SourceProduct',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/SourceProductDataResource'
                )
            ),
        ]
    )]
    public function store(StoreSourceProductRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    #[OA\Get(
        path: '/api/source_products/{id}',
        description: 'Get SourceProduct by id',
        summary: 'Get SourceProduct by id',
        tags: ['SourceProducts'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the SourceProduct',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 4,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get SourceProduct by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/SourceProductDataResource'
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    #[OA\Patch(
        path: '/api/source_products/{id}',
        description: 'Update SourceProduct by id',
        summary: 'Update SourceProduct by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateSourceProductRequest'
            )
        ),
        tags: ['SourceProducts'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the SourceProduct',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 4,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update SourceProduct by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/SourceProductDataResource'
                )
            ),
        ]
    )]
    public function update(UpdateSourceProductRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    #[OA\Delete(
        path: '/api/source_products/{id}',
        description: 'Delete SourceProduct by id',
        summary: 'Delete SourceProduct by id',
        tags: ['SourceProducts'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Delete SourceProduct by id',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 4,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Delete SourceProduct by id',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'success',
                            type: 'boolean',
                            example: true,
                        ),
                    ]
                )
            ),
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        return $this->destroyAction($id);
    }
}
