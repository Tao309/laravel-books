<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BookSeries\StoreBookSeriesRequest;
use App\Http\Requests\BookSeries\UpdateBookSeriesRequest;
use App\Services\BookSeriesService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class BookSeriesController extends ApiController
{
    public function __construct(
        BookSeriesService $service,
    ) {
        $this->service = $service;
    }

    #[OA\Get(
        path: '/api/books_series',
        description: 'Get list of Book Series',
        summary: 'Get list of Book Series',
        requestBody: new OA\RequestBody,
        tags: ['BookSeries'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get list of Book Series',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BookSeriesResourceCollection'
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return $this->indexAction();
    }

    #[OA\Post(
        path: '/api/books_series',
        description: 'Create BookSeries',
        summary: 'Create BookSeries',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreBookSeriesRequest'
            )
        ),
        tags: ['BookSeries'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get created BookSeries',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BookSeriesDataResource'
                )
            ),
        ]
    )]
    public function store(StoreBookSeriesRequest $request): JsonResponse
    {
        return $this->storeAction($request);
    }

    #[OA\Get(
        path: '/api/books_series/{id}',
        description: 'Get BookSeries by id',
        summary: 'Get BookSeries by id',
        tags: ['BookSeries'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the BookSeries',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 4,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get BookSeries by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BookSeriesDataResource'
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        return $this->showAction($id);
    }

    #[OA\Patch(
        path: '/api/books_series/{id}',
        description: 'Update BookSeries by id',
        summary: 'Update BookSeries by id',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateBookSeriesRequest'
            )
        ),
        tags: ['BookSeries'],
        parameters: [
            new OA\Parameter(
                parameter: 'id',
                name: 'id',
                description: 'Id of the BookSeries',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 4,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Update BookSeries by id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BookSeriesDataResource'
                )
            ),
        ]
    )]
    public function update(UpdateBookSeriesRequest $request, int $id): JsonResponse
    {
        return $this->updateAction($request, $id);
    }
}
