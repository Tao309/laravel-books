<?php

namespace App\OpenApi\Schemas\PaperTypes;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdatePaperTypeRequest',
    properties: [
        new OA\Property(property: 'label', type: 'string', example: 'Типографская'),
        new OA\Property(
            property: 'description',
            type: 'string',
            example: 'Бумага средней плотности, светло-серая, слегка шероховатая'
        ),
    ],
    type: 'object'
)]
class UpdatePaperTypeRequestSchema {}
