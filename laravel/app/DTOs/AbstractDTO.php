<?php

namespace App\DTOs;

abstract class AbstractDTO implements DTOInterface
{
    public function toArray(): array
    {
        return array_merge($this->getEntityData(), $this->getRelationsData());
    }
}
