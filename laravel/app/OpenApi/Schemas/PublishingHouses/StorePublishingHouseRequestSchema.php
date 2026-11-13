<?php

namespace App\OpenApi\Schemas\PublishingHouses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StorePublishingHouseRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'АСТ'),
    ],
    type: 'object'
)]
class StorePublishingHouseRequestSchema {}
