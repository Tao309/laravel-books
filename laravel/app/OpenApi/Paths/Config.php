<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Config
{
    #[OA\Get(
        path: '/api/config',
        operationId: 'config.show',
        description: 'Get application config',
        summary: 'Get application config',
        tags: ['Config'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Get application config',
                content: new OA\JsonContent(
                    ref: Refs::CONFIG_RESPONSE
                )
            ),
        ]
    )]
    public function show(): void {}
}
