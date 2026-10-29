<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Cities\StoreCityRequest;
use App\Http\Requests\Cities\UpdateCityRequest;
use App\Services\CityService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CityController extends ApiController
{
    public function __construct(
        CityService $service,
    ) {
        $this->service = $service;
    }

    #[OA\Get(
        path: '/api/cities',
        description: 'Get list of cities',
        summary: 'Get list of cities',
        requestBody: new OA\RequestBody,
        tags: ['Cities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of cities',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CityCollection'
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->indexAction();
    }

    #[OA\Post(
        path: '/api/cities',
        description: 'Create City',
        summary: 'Create City',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreCityRequest'
            )
        ),
        tags: ['Cities'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created City',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CityDataResource'
                )
            ),
        ]
    )]
    public function store(StoreCityRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    #[OA\Patch(
        path: '/api/cities/{id}',
        description: 'Update City by id',
        summary: 'Update City by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateCityRequest'
            )
        ),
        tags: ['Cities'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the City',
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
                description: 'Update City by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/CityDataResource'
                )
            ),
        ]
    )]
    public function update(UpdateCityRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }

    #[OA\Delete(
        path: '/api/cities/{id}',
        description: 'Delete City by id',
        summary: 'Delete City by id',
        tags: ['Cities'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Delete City by id',
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
                description: 'Delete City by id',
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
