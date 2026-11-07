<?php

namespace Database\Seeders;

use App\Auth\CustomUserProvider;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use App\Models\User;
use App\Repositories\SourceProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class SourceProductSeeder extends Seeder
{
    public function __construct(
        private readonly SourceProductRepository $sourceProductRepository,
    ) {}

    public function run(): void
    {
        $user = $this->getUsers()->firstOrFail();
        $sourceProductTypes = $this->getSourceProductTypes();

        Auth::attempt([
            CustomUserProvider::TOKEN => $user->remember_token,
        ], true);

        $this->sourceProductRepository->create([
            SourceProduct::PARAM_TITLE => 'Кроссовки для бега Strobbs',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => $sourceProductTypes->filter(function (SourceProductType $spt) {
                return $spt->code === 'shoes';
            })->firstOrFail()->id,
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
            SourceProductUserData::PARAM_COMMENT => 'Комментарий для Кроссовки для бега Strobbs',
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 5200,
            ],
        ]);

        $this->sourceProductRepository->create([
            SourceProduct::PARAM_TITLE => 'Гранола 1 кг',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => $sourceProductTypes->filter(function (SourceProductType $spt) {
                return $spt->code === 'food';
            })->firstOrFail()->id,
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                SourceProductUserData::PARAM_COMMENT => 'Комментарий для Гранола 1 кг',
            ],
        ]);

        $this->sourceProductRepository->create([
            SourceProduct::PARAM_TITLE => 'Пальто демисезонное',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => $sourceProductTypes->filter(function (SourceProductType $spt) {
                return $spt->code === 'outerwear';
            })->firstOrFail()->id,
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                SourceProductUserData::PARAM_COMMENT => 'Комментарий для Гранола 1 кг',
                SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => 620,
            ],
        ]);

        $this->sourceProductRepository->create([
            SourceProduct::PARAM_TITLE => 'Куртка зимняя',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => $sourceProductTypes->filter(function (SourceProductType $spt) {
                return $spt->code === 'outerwear';
            })->firstOrFail()->id,
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                SourceProductUserData::PARAM_COMMENT => 'Комментарий для Куртка зимняя',
            ],
        ]);

        $this->sourceProductRepository->create([
            SourceProduct::PARAM_TITLE => 'Рубашка льняная с рукавами на лето',
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => $sourceProductTypes->filter(function (SourceProductType $spt) {
                return $spt->code === 'shirts';
            })->firstOrFail()->id,
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => [
                SourceProductUserData::PARAM_COMMENT => 'Комментарий для Рубашка льняная с рукавами на лето',
            ],
        ]);
    }

    private function getSourceProductTypes(): Collection
    {
        return SourceProductType::all();
    }

    private function getUsers(): Collection
    {
        return User::all();
    }
}
