<?php

namespace Database\Seeders;

use App\DTOs\SourceProductTypeDTO;
use App\Models\SourceProductType;
use App\Repositories\SourceProductTypeRepository;

class SourceProductTypeSeeder extends AbstractSeeder
{
    public function __construct(
        private readonly SourceProductTypeRepository $repo,
    ) {}

    public function run(): void
    {
        foreach ($this->getSeedData() as $data) {
            $this->repo->create(SourceProductTypeDTO::fromArray([
                SourceProductType::PARAM_CODE => $data[0],
                SourceProductType::PARAM_NAME => $data[1],
            ]));
        }
    }

    private function getSeedData(): array
    {
        return [
            ['books', 'Книги'],
            ['shoes', 'Обувь'],
            ['shirts', 'Рубашки'],
            ['beverages', 'Напитки'],
            ['medicines', 'Лекарства'],
            ['food', 'Еда'],
            ['shorts', 'Шорты'],
            ['nuts', 'Орехи'],
            ['toys', 'Игрушки'],
            ['bags', 'Сумки'],
            ['acoustics', 'Акустика'],
            ['technic', 'Техника'],
            ['outerwear', 'Верхняя одежда'],
        ];
    }
}
