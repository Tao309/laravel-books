<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Books
{
    #[OA\Get(
        path: '/api/books',
        operationId: 'books.index',
        description: 'Get list of books',
        summary: 'Get list of books',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of books',
                content: new OA\JsonContent(
                    ref: Refs::BOOKS_PAGINATE_RESOURCE_COLLECTION
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/books',
        operationId: 'books.store',
        description: 'Create Book',
        summary: 'Create Book',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_BOOK_REQUEST
            )
        ),
        tags: ['Books'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created Book',
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/books/{id}',
        operationId: 'books.show',
        description: 'Get Book by id',
        summary: 'Get Book by id',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get Book by id',
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Patch(
        path: '/api/books/{id}',
        operationId: 'books.update',
        description: 'Update Book by id',
        summary: 'Update Book by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_BOOK_REQUEST
            )
        ),
        tags: ['Books'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update Book by id',
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SHOW_RESPONSE
                )
            ),
        ]
    )]
    public function update(): void {}

    #[OA\Delete(
        path: '/api/books/{id}',
        operationId: 'books.destroy',
        description: 'Delete Book by id',
        summary: 'Delete Book by id',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Delete Book by id',
                content: new OA\JsonContent(
                    ref: Refs::BOOK_DESTROY_RESPONSE
                )
            ),
        ]
    )]
    public function destroy(): void {}
}
