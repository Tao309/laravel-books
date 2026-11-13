<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class BookUserData
{
    #[OA\Get(
        path: '/api/book_user_data/{book_id}',
        operationId: 'book_user_data.show',
        description: 'Get BookUserData by id',
        summary: 'Get BookUserData by Book ID',
        tags: ['BookUserData'],
        parameters: [
            new OA\Parameter(ref: Refs::BOOK_ID),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get BookUserData by book_id',
                content: new OA\JsonContent(
                    ref: Refs::BOOK_USER_DATA_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}
}
