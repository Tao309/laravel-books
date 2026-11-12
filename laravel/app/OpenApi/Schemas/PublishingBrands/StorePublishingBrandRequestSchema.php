<?php

namespace App\OpenApi\Schemas\PublishingBrands;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 'Астрель СПб', property: 'name', type: 'string'),
    ],
    schema: 'StorePublishingBrandRequest',
    type: 'object'
)]
class StorePublishingBrandRequestSchema {}
