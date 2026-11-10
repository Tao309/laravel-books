<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SearchValueRequest;
use App\Http\Resources\SourceProducts\SourceProductsDataResourceCollection;
use App\Services\SearchSourceProductsService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SearchSourceProductsController extends ApiController
{
    public function __construct(
        private readonly SearchSourceProductsService $searchService,
    ) {}

    #[OA\Post(
        path: '/api/source_products/search/{value}',
        description: 'Search SourceProducts by value',
        summary: 'Search SourceProducts by value',
        tags: ['SourceProducts'],
        parameters: [
            new OA\Parameter(
                parameter: 'value',
                name: 'value',
                description: 'Some words for search',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    example: 'Игрушка',
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Search SourceProducts by value',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/SourceProductsDataResourceCollection'
                )
            ),
        ]
    )]
    public function __invoke(
        SearchValueRequest $request
    ): JsonResponse {
        return $this->toJson(
            SourceProductsDataResourceCollection::make(
                $this->searchService->searchByValue($request->validated(SearchValueRequest::FIELD_VALUE))
            )
        );
    }
}
