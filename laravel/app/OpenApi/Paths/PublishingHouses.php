<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class PublishingHouses
{
    #[OA\Get(
        path: '/api/publishing_houses',
        operationId: 'publishing_houses.index',
        description: 'Get list of Publishing Houses',
        summary: 'Get list of Publishing Houses',
        tags: ['PublishingHouses'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of Publishing Houses',
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_HOUSES_INDEX_RESPONSE
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/publishing_houses',
        operationId: 'publishing_houses.store',
        description: 'Create PublishingHouse',
        summary: 'Create PublishingHouse',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_PUBLISHING_HOUSE_REQUEST
            )
        ),
        tags: ['PublishingHouses'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created PublishingHouse',
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_HOUSE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/publishing_houses/{id}',
        operationId: 'publishing_houses.show',
        description: 'Get PublishingHouse by id',
        summary: 'Get PublishingHouse by id',
        tags: ['PublishingHouses'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get PublishingHouse by id',
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_HOUSE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Patch(
        path: '/api/publishing_houses/{id}',
        operationId: 'publishing_houses.update',
        description: 'Update PublishingHouse by id',
        summary: 'Update PublishingHouse by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_PUBLISHING_HOUSE_REQUEST
            )
        ),
        tags: ['PublishingHouses'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update PublishingHouse by id',
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_HOUSE_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function update(): void {}
}
