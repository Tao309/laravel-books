<?php

namespace App\DTOs;

abstract class AbstractDTO implements DtoInterface
{
    public function toArray(): array
    {
        return array_merge($this->getEntityData(), $this->getRelationsData());
    }
}
