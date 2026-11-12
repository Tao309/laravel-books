<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Authors
{
    #[OA\Get(
        path: '/api/authors',
        operationId: 'authors.index',
        description: 'Get list of authors',
        summary: 'Get list of authors',
        tags: ['Authors'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of authors',
                content: new OA\JsonContent(
                    ref: Refs::AUTHORS_PAGINATE_RESOURCE_COLLECTION
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/authors',
        operationId: 'authors.store',
        description: 'Create Author',
        summary: 'Create Author',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_AUTHOR_REQUEST
            )
        ),
        tags: ['Authors'],
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::AUTHOR_DATA_RESOURCE
                ),
                description: 'Get created Author',
                response: 201
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/authors/{id}',
        operationId: 'authors.show',
        description: 'Get author by id',
        summary: 'Get author by id',
        tags: ['Authors'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get author by id',
                content: new OA\JsonContent(
                    ref: Refs::AUTHOR_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Patch(
        path: '/api/authors/{id}',
        operationId: 'authors.update',
        description: 'Update author by id',
        summary: 'Update author by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_AUTHOR_REQUEST
            )
        ),
        tags: ['Authors'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update author by id',
                content: new OA\JsonContent(
                    ref: Refs::AUTHOR_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/authors/{id}',
        operationId: 'authors.destroy',
        description: 'Delete author by id',
        summary: 'Delete author by id',
        tags: ['Authors'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Delete author by id',
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
    public function destroy(): void {}
}
