<?php

namespace Tests\Unit\DTOs;

use App\DTOs\BindingTyperDTO;
use App\DTOs\DtoInterface;
use App\Models\BindingType;
use PHPUnit\Framework\TestCase;

class BindingTypeDTOTest extends TestCase
{
    public function test_from_array_maps_entity_data(): void
    {
        $dto = BindingTyperDTO::fromArray([BindingType::PARAM_LABEL => 'Твёрдый переплёт']);

        $this->assertInstanceOf(DtoInterface::class, $dto);
        $this->assertSame('Твёрдый переплёт', $dto->label);
        $this->assertSame([BindingType::PARAM_LABEL => 'Твёрдый переплёт'], $dto->getEntityData());
        $this->assertSame([], $dto->getRelationsData());
        $this->assertSame($dto->getEntityData(), $dto->toArray());
    }

    public function test_from_array_uses_data_key_as_label(): void
    {
        $dto = BindingTyperDTO::fromArray(['label' => 'Мягкая обложка', 'extra' => 'ignored']);

        $this->assertSame('Мягкая обложка', $dto->label);
        $this->assertSame([BindingType::PARAM_LABEL => 'Мягкая обложка'], $dto->getEntityData());
    }
}
