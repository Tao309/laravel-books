<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Cities
{
    #[OA\Get(
        description: 'Get list of cities',
        operationId: 'cities.index',
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        path: '/api/cities',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::CITIES_DATA_RESOURCE_COLLECTION
                ),
                description: 'Get list of cities',
                response: 200
            ),
        ],
        summary: 'Get list of cities',
        tags: ['Cities']
    )]
    public function index(): void {}

    #[OA\Post(
        description: 'Create City',
        operationId: 'cities.store',
        path: '/api/cities',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::STORE_CITY_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::CITY_DATA_RESOURCE
                ),
                description: 'Get created City',
                response: 201
            ),
        ],
        summary: 'Create City',
        tags: ['Cities']
    )]
    public function store(): void {}

    #[OA\Patch(
        description: 'Update City by id',
        operationId: 'cities.update',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/cities/{id}',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::UPDATE_CITY_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::CITY_DATA_RESOURCE
                ),
                description: 'Update City by id',
                response: 200
            ),
        ],
        summary: 'Update City by id',
        tags: ['Cities']
    )]
    public function update(): void {}

    #[OA\Delete(
        description: 'Delete City by id',
        operationId: 'cities.destroy',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/cities/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            example: true,
                            property: 'success',
                            type: 'boolean',
                        ),
                    ]
                ),
                description: 'Delete City by id',
                response: 200
            ),
        ],
        summary: 'Delete City by id',
        tags: ['Cities']
    )]
    public function destroy(): void {}
}
