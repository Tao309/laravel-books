<?php

namespace App\OpenApi\Schemas\PublishingBrands;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdatePublishingBrandRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Астрель СПб'),
    ],
    type: 'object'
)]
class UpdatePublishingBrandRequestSchema {}
