<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PaperTypes\StorePaperTypeRequest;
use App\Http\Requests\PaperTypes\UpdatePaperTypeRequest;
use App\Http\Requests\QueryQueryCollectionRequest;
use App\Services\PaperTypeService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PaperTypeController extends ApiController
{
    public function __construct(
        PaperTypeService $service,
    ) {
        $this->service = $service;
    }

    #[OA\Get(
        path: '/api/paper_types',
        description: 'Get list of PaperTypes',
        summary: 'Get list of PaperTypes',
        requestBody: new OA\RequestBody,
        tags: ['PaperTypes'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/Ids'),
            new OA\Parameter(ref: '#/components/parameters/Limit'),
            new OA\Parameter(ref: '#/components/parameters/Search'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of PaperTypes',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PaperTypesDataResourceCollection'
                )
            ),
        ]
    )]
    public function index(QueryQueryCollectionRequest $request): JsonResponse
    {
        return $this->indexAction($request);
    }

    #[OA\Post(
        path: '/api/paper_types',
        description: 'Create PaperType',
        summary: 'Create PaperType',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StorePaperTypeRequest'
            )
        ),
        tags: ['PaperTypes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created PaperType',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PaperTypeDataResource'
                )
            ),
        ]
    )]
    public function store(StorePaperTypeRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    #[OA\Get(
        path: '/api/paper_types/{id}',
        description: 'Get PaperType by id',
        summary: 'Get PaperType by id',
        tags: ['PaperTypes'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the PaperType',
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
                description: 'Get PaperType by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PaperTypeDataResource'
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    #[OA\Patch(
        path: '/api/paper_types/{id}',
        description: 'Update PaperType by id',
        summary: 'Update PaperType by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdatePaperTypeRequest'
            )
        ),
        tags: ['PaperTypes'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the PaperType',
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
                description: 'Update PaperType by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/PaperTypeDataResource'
                )
            ),
        ]
    )]
    public function update(UpdatePaperTypeRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    #[OA\Delete(
        path: '/api/paper_types/{id}',
        description: 'Delete PaperType by id',
        summary: 'Delete PaperType by id',
        tags: ['PaperTypes'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Delete PaperType by id',
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
                description: 'Delete PaperType by id',
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
