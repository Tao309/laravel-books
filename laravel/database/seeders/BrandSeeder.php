<?php

namespace Database\Seeders;

use App\DTOs\BrandDTO;
use App\Enums\BrandCode;
use App\Models\Brand;
use App\Repositories\BrandRepository;

class BrandSeeder extends AbstractSeeder
{
    public function __construct(
        private readonly BrandRepository $brandRepository,
    ) {}

    public function run(): void
    {
        foreach ($this->getData() as $data) {
            $this->brandRepository->create(
                BrandDTO::fromArray([
                    Brand::PARAM_CODE => $data[0],
                    Brand::PARAM_TITLE => $data[1],
                ])
            );
        }
    }

    private function getData(): array
    {
        return [
            [BrandCode::JOYTOY->value, 'JoyToy'],
            [BrandCode::XIAOMI->value, 'Xiaomi'],
            [BrandCode::NECA->value, 'Neca'],
            [BrandCode::APPLE->value, 'Apple'],
        ];
    }
}
