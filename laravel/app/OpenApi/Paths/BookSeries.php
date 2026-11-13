<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class BookSeries
{
    #[OA\Get(
        path: '/api/books_series',
        operationId: 'book_series.index',
        description: 'Get list of Book Series',
        summary: 'Get list of Book Series',
        tags: ['BookSeries'],
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of Book Series',
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SERIES_DATA_RESOURCE_COLLECTION
                )
            ),
        ]
    )]
    public function index(): void {}

    #[OA\Post(
        path: '/api/books_series',
        operationId: 'book_series.store',
        description: 'Create BookSeries',
        summary: 'Create BookSeries',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::STORE_BOOK_SERIES_REQUEST
            )
        ),
        tags: ['BookSeries'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Get created BookSeries',
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SERIES_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function store(): void {}

    #[OA\Get(
        path: '/api/books_series/{id}',
        operationId: 'book_series.show',
        description: 'Get BookSeries by id',
        summary: 'Get BookSeries by id',
        tags: ['BookSeries'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get BookSeries by id',
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SERIES_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function show(): void {}

    #[OA\Patch(
        path: '/api/books_series/{id}',
        operationId: 'book_series.update',
        description: 'Update BookSeries by id',
        summary: 'Update BookSeries by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: Refs::UPDATE_BOOK_SERIES_REQUEST
            )
        ),
        tags: ['BookSeries'],
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update BookSeries by id',
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SERIES_DATA_RESOURCE
                )
            ),
        ]
    )]
    public function update(): void {}
}
