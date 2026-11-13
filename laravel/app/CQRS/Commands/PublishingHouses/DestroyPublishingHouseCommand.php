<?php

namespace App\CQRS\Commands\PublishingHouses;

class DestroyPublishingHouseCommand
{
    public function __construct(public int $id) {}
}
