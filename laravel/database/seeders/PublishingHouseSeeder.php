<?php

namespace Database\Seeders;

use App\DTOs\PublishingHouseDTO;
use App\Models\PublishingHouse;
use App\Repositories\PublishingHouseRepository;

class PublishingHouseSeeder extends AbstractSeeder
{
    public function __construct(
        private readonly PublishingHouseRepository $repo
    ) {}

    public function run(): void
    {
        foreach ($this->getData() as $name) {
            $this->repo->create(PublishingHouseDTO::fromArray([
                PublishingHouse::NAME => $name,
            ]));
        }
    }

    public function getData(): array
    {
        return [
            'АСТ',
            'Эксмо',
            'Манн, Иванов и Фербер',
            'Альпина Паблишер',
            'Росмэн',
        ];
    }
}
