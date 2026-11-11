<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\IndexRequest;
use App\Http\Requests\SourceProductTypes\StoreSourceProductTypeRequest;
use App\Http\Requests\SourceProductTypes\UpdateSourceProductTypeRequest;
use App\Services\SourceProductTypeService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SourceProductTypeController extends ApiController
{
    public function __construct(
        SourceProductTypeService $service,
    ) {
        $this->service = $service;
    }

    #[OA\Get(
        path: '/api/source_product_types',
        description: 'Get list of SourceProductTypes',
        summary: 'Get list of SourceProductTypes',
        requestBody: new OA\RequestBody,
        tags: ['SourceProductTypes'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/Ids'),
            new OA\Parameter(ref: '#/components/parameters/Limit'),
            new OA\Parameter(ref: '#/components/parameters/Search'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of SourceProductTypes',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/SourceProductTypesDataResourceCollection'
                )
            ),
        ]
    )]
    public function index(IndexRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    #[OA\Post(
        path: '/api/source_product_types',
        description: 'Create SourceProductType',
        summary: 'Create SourceProductType',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreSourceProductTypeRequest'
            )
        ),
        tags: ['SourceProductTypes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created SourceProductType',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/SourceProductTypeDataResource'
                )
            ),
        ]
    )]
    public function store(StoreSourceProductTypeRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    #[OA\Get(
        path: '/api/source_product_types/{id}',
        description: 'Get SourceProductType by id',
        summary: 'Get SourceProductType by id',
        tags: ['SourceProductTypes'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the SourceProductType',
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
                description: 'Get SourceProductType by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/SourceProductTypeDataResource'
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    #[OA\Patch(
        path: '/api/source_product_types/{id}',
        description: 'Update SourceProductType by id',
        summary: 'Update SourceProductType by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateSourceProductTypeRequest'
            )
        ),
        tags: ['SourceProductTypes'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the SourceProductType',
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
                description: 'Update SourceProductType by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/SourceProductTypeDataResource'
                )
            ),
        ]
    )]
    public function update(UpdateSourceProductTypeRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    #[OA\Delete(
        path: '/api/source_product_types/{id}',
        description: 'Delete SourceProductType by id',
        summary: 'Delete SourceProductType by id',
        tags: ['SourceProductTypes'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Delete SourceProductType by id',
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
                description: 'Delete SourceProductType by id',
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
