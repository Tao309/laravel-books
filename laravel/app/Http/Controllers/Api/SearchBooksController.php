<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SearchValueRequest;
use App\Http\Resources\Books\BooksDataResourceCollection;
use App\Services\SearchBooksService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SearchBooksController extends ApiController
{
    public function __construct(
        private readonly SearchBooksService $searchService,
    ) {}

    #[OA\Post(
        path: '/api/books/search/{value}',
        description: 'Search Books by value',
        summary: 'Search Books by value',
        tags: ['Books'],
        parameters: [
            new OA\Parameter(
                parameter: 'value',
                name: 'value',
                description: 'Some words for search',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    example: 'Дом',
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Search Books by value',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BooksDataResourceCollection'
                )
            ),
        ]
    )]
    public function __invoke(
        SearchValueRequest $request
    ): JsonResponse {
        return $this->toJson(
            BooksDataResourceCollection::make(
                $this->searchService->searchByValue($request->validated(SearchValueRequest::FIELD_VALUE))
            )
        );
    }
}
