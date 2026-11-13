<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Cities
{
    #[OA\Get(
        path: '/api/cities',
        operationId: 'cities.index',
        description: 'Get list of cities',
        summary: 'Get list of cities',
        tags: ['Cities'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of cities',
                content: new OA\JsonContent(
                    ref: Refs::CITIES_INDEX_RESPONSE
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/cities',
        operationId: 'cities.store',
        description: 'Create City',
        summary: 'Create City',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_CITY_REQUEST
            )
        ),
        tags: ['Cities'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created City',
                content: new OA\JsonContent(
                    ref: Refs::CITY_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Patch(
        path: '/api/cities/{id}',
        operationId: 'cities.update',
        description: 'Update City by id',
        summary: 'Update City by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_CITY_REQUEST
            )
        ),
        tags: ['Cities'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update City by id',
                content: new OA\JsonContent(
                    ref: Refs::CITY_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/cities/{id}',
        operationId: 'cities.destroy',
        description: 'Delete City by id',
        summary: 'Delete City by id',
        tags: ['Cities'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Delete City by id',
                content: new OA\JsonContent(
                    ref: Refs::CITY_DESTROY_RESPONSE
                )
            ),
        ]
    )]
    public function destroy(): void {}
}
