<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class BookUserData
{
    #[OA\Get(
        description: 'Get BookUserData by id',
        operationId: 'book_user_data.show',
        parameters: [
            new OA\Parameter(ref: Refs::BOOK_ID),
        ],
        path: '/api/book_user_data/{book_id}',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::BOOK_USER_DATA_RESOURCE
                ),
                description: 'Get BookUserData by book_id',
                response: 200
            ),
        ],
        summary: 'Get BookUserData by Book ID',
        tags: ['BookUserData']
    )]
    public function show(): void {}
}
