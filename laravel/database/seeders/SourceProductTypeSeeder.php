<?php

namespace Database\Seeders;

use App\Models\SourceProductType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SourceProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getSeedData() as $data) {
            SourceProductType::create([
                SourceProductType::PARAM_CODE => $data[0],
                SourceProductType::PARAM_NAME => $data[1],
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ]);
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
