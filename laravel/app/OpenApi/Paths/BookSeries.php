<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class BookSeries
{
    #[OA\Get(
        description: 'Get list of Book Series',
        operationId: 'book_series.index',
        parameters: [
            new OA\Parameter(ref: Refs::IDS),
            new OA\Parameter(ref: Refs::LIMIT),
            new OA\Parameter(ref: Refs::SEARCH),
        ],
        path: '/api/books_series',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SERIES_DATA_RESOURCE_COLLECTION
                ),
                description: 'Get list of Book Series',
                response: 200
            ),
        ],
        summary: 'Get list of Book Series',
        tags: ['BookSeries']
    )]
    public function index(): void {}

    #[OA\Post(
        description: 'Create BookSeries',
        operationId: 'book_series.store',
        path: '/api/books_series',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::STORE_BOOK_SERIES_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SERIES_DATA_RESOURCE
                ),
                description: 'Get created BookSeries',
                response: 201
            ),
        ],
        summary: 'Create BookSeries',
        tags: ['BookSeries']
    )]
    public function store(): void {}

    #[OA\Get(
        description: 'Get BookSeries by id',
        operationId: 'book_series.show',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/books_series/{id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SERIES_DATA_RESOURCE
                ),
                description: 'Get BookSeries by id',
                response: 200
            ),
        ],
        summary: 'Get BookSeries by id',
        tags: ['BookSeries']
    )]
    public function show(): void {}

    #[OA\Patch(
        description: 'Update BookSeries by id',
        operationId: 'book_series.update',
        parameters: [
            new OA\Parameter(ref: Refs::ID),
        ],
        path: '/api/books_series/{id}',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: Refs::UPDATE_BOOK_SERIES_REQUEST
            ),
            required: true
        ),
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::BOOK_SERIES_DATA_RESOURCE
                ),
                description: 'Update BookSeries by id',
                response: 200
            ),
        ],
        summary: 'Update BookSeries by id',
        tags: ['BookSeries']
    )]
    public function update(): void {}
}
