<?php

namespace Database\Seeders;

use App\DTOs\BookSeriesDTO;
use App\Models\BookSeries;
use App\Repositories\BookSeriesRepository;

class BookSeriesSeeder extends AbstractSeeder
{
    public function __construct(
        private readonly BookSeriesRepository $repo
    ) {}

    public function run(): void
    {
        foreach ($this->getData() as $name) {
            $this->repo->create(BookSeriesDTO::fromArray([
                BookSeries::PARAM_NAME => $name,
            ]));
        }
    }

    public function getData(): array
    {
        return [
            'Звезды научной фантастики',
            'НоваФикшн. Фантастика: XXI век',
            'Nova Fiction. Русская городская фэнтези',
            'Young Adult. Корейские романы',
            'Red Violet. Темные миры',
            'МИФ x Шуфлядка Писателя',
            'Кинг: книжная полка',
        ];
    }
}
