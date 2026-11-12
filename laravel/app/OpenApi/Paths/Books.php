<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Books
{
    #[OA\Get(
        description: 'Get list of books',
        operationId: 'books.index',
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        path: '/api/books',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::BOOKS_PAGINATE_RESOURCE_COLLECTION
                ),
                description: 'Get list of books',
                response: 200
            ),
        ],
        summary: 'Get list of books',
        tags: ['Books']
    )]
    public function index(): void {}

    #[OA\Post(
        description: 'Create Book',
        operationId: 'books.store',
        path: '/api/books',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::STORE_BOOK_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::BOOK_DATA_RESOURCE
                ),
                description: 'Get created Book',
                response: 201
            ),
        ],
        summary: 'Create Book',
        tags: ['Books']
    )]
    public function store(): void {}

    #[OA\Get(
        description: 'Get Book by id',
        operationId: 'books.show',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/books/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::BOOK_DATA_RESOURCE
                ),
                description: 'Get Book by id',
                response: 200
            ),
        ],
        summary: 'Get Book by id',
        tags: ['Books']
    )]
    public function show(): void {}

    #[OA\Patch(
        description: 'Update Book by id',
        operationId: 'books.update',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/books/{id}',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::UPDATE_BOOK_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::BOOK_DATA_RESOURCE
                ),
                description: 'Update Book by id',
                response: 200
            ),
        ],
        summary: 'Update Book by id',
        tags: ['Books']
    )]
    public function update(): void {}

    #[OA\Delete(
        description: 'Delete Book by id',
        operationId: 'books.destroy',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/books/{id}',
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
                description: 'Delete Book by id',
                response: 200
            ),
        ],
        summary: 'Delete Book by id',
        tags: ['Books']
    )]
    public function destroy(): void {}
}
