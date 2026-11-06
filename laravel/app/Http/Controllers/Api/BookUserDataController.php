<?php

namespace App\Http\Controllers\Api;

use App\Repositories\BookUserDataRepository;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class BookUserDataController extends ApiController
{
    public function __construct(
        private readonly BookUserDataRepository $bookUserDataRepository,
    ) {}

    #[OA\Get(
        path: '/api/book_user_data/{book_id}',
        description: 'Get BookUserData by id',
        summary: 'Get BookUserData by Book ID',
        tags: ['BookUserData'],
        parameters: [
            new OA\Parameter(
                parameter: 'book_id',
                name: 'book_id',
                description: 'Id of the Book',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer',
                    example: 2,
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get BookUserData by book_id and user_id',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/BookUserDataResource'
                )
            ),
        ]
    )]
    public function show(int $bookId): JsonResponse
    {
        return $this->toJson($this->bookUserDataRepository->get($bookId)->toResource());
    }
}
