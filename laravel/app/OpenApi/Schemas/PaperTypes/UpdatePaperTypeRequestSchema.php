<?php

namespace App\OpenApi\Schemas\PaperTypes;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 'Типографская', property: 'label', type: 'string'),
        new OA\Property(
            example: 'Бумага средней плотности, светло-серая, слегка шероховатая',
            property: 'description',
            type: 'string'
        ),
    ],
    schema: 'UpdatePaperTypeRequest',
    type: 'object'
)]
class UpdatePaperTypeRequestSchema {}
