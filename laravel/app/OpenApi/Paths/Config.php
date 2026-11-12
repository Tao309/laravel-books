<?php

namespace App\OpenApi\Paths;

use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

final class Config
{
    #[OA\Get(
        description: 'Get application config',
        operationId: 'config.show',
        path: '/api/config',
        responses: [
            new OA\Response(
                content: new OA\JsonContent(
                    ref: Refs::CONFIG_RESOURCE
                ),
                description: 'Get application config',
                response: 200
            ),
        ],
        summary: 'Get application config',
        tags: ['Config']
    )]
    public function show(): void {}
}
