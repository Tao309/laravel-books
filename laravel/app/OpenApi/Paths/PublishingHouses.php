<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class PublishingHouses
{
    #[OA\Get(
        description: 'Get list of Publishing Houses',
        operationId: 'publishing_houses.index',
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        path: '/api/publishing_houses',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_HOUSES_DATA_RESOURCE_COLLECTION
                ),
                description: 'Get list of Publishing Houses',
                response: 200
            ),
        ],
        summary: 'Get list of Publishing Houses',
        tags: ['PublishingHouses']
    )]
    public function index(): void {}

    #[OA\Post(
        description: 'Create PublishingHouse',
        operationId: 'publishing_houses.store',
        path: '/api/publishing_houses',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::STORE_PUBLISHING_HOUSE_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_HOUSE_DATA_RESOURCE
                ),
                description: 'Get created PublishingHouse',
                response: 201
            ),
        ],
        summary: 'Create PublishingHouse',
        tags: ['PublishingHouses']
    )]
    public function store(): void {}

    #[OA\Get(
        description: 'Get PublishingHouse by id',
        operationId: 'publishing_houses.show',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/publishing_houses/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_HOUSE_DATA_RESOURCE
                ),
                description: 'Get PublishingHouse by id',
                response: 200
            ),
        ],
        summary: 'Get PublishingHouse by id',
        tags: ['PublishingHouses']
    )]
    public function show(): void {}

    #[OA\Patch(
        description: 'Update PublishingHouse by id',
        operationId: 'publishing_houses.update',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/publishing_houses/{id}',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::UPDATE_PUBLISHING_HOUSE_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::PUBLISHING_HOUSE_DATA_RESOURCE
                ),
                description: 'Update PublishingHouse by id',
                response: 200
            ),
        ],
        summary: 'Update PublishingHouse by id',
        tags: ['PublishingHouses']
    )]
    public function update(): void {}
}
