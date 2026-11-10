<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SearchValueRequest;
use App\Http\Resources\Authors\AuthorsDataResourceCollection;
use App\Services\SearchAuthorsService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SearchAuthorsController extends ApiController
{
    public function __construct(
        private readonly SearchAuthorsService $searchService,
    ) {}

    #[OA\Post(
        path: '/api/authors/search/{value}',
        description: 'Search Authors by value',
        summary: 'Search Authors by value',
        tags: ['Authors'],
        parameters: [
            new OA\Parameter(
                parameter: 'value',
                name: 'value',
                description: 'Some words for search',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    example: 'Лев',
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Search Authors by value',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/AuthorsDataResourceCollection'
                )
            ),
        ]
    )]
    public function __invoke(
        SearchValueRequest $request
    ): JsonResponse {
        return $this->toJson(
            AuthorsDataResourceCollection::make(
                $this->searchService->searchByValue($request->validated(SearchValueRequest::FIELD_VALUE))
            )
        );
    }
}
