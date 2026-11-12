<?php

namespace App\OpenApi\Schemas\PublishingHouses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 'АСТ', property: 'name', type: 'string'),
    ],
    schema: 'StorePublishingHouseRequest',
    type: 'object'
)]
class StorePublishingHouseRequestSchema {}
