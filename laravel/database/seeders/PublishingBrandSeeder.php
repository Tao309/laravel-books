<?php

namespace Database\Seeders;

use App\DTOs\PublishingBrandDTO;
use App\Models\PublishingBrand;
use App\Repositories\PublishingBrandRepository;

class PublishingBrandSeeder extends AbstractSeeder
{
    public function __construct(
        private readonly PublishingBrandRepository $repo
    ) {}

    public function run(): void
    {
        foreach ($this->getData() as $name) {
            $this->repo->create(PublishingBrandDTO::fromArray([
                PublishingBrand::NAME => $name,
            ]));
        }
    }

    public function getData(): array
    {
        return [
            'Астрель СПб',
            'Молодежная литература. Фантастика и фэнтези',
            'Like Book',
            'fanzon',
            'Inspiria',
            'Вилли-Винки',
        ];
    }
}
