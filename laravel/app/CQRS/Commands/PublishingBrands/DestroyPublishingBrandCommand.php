<?php

namespace App\CQRS\Commands\PublishingBrands;

class DestroyPublishingBrandCommand
{
    public function __construct(public int $id) {}
}
