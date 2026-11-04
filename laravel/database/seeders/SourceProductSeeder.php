<?php

namespace Database\Seeders;

use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SourceProductSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->getUsers();
        $sourceProductTypes = $this->getSourceProductTypes();

        SourceProduct::create([
            SourceProduct::PARAM_TITLE => 'Кроссовки для бега Strobbs',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),

            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $sourceProductTypes->filter(function (SourceProductType $spt) {
                return $spt->code === 'shoes';
            })->firstOrFail()->id,
            SourceProduct::PARAM_USER_CREATED_ID => $users->firstOrFail()->id,
        ]);

        SourceProduct::create([
            SourceProduct::PARAM_TITLE => 'Гранола 1 кг',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),

            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $sourceProductTypes->filter(function (SourceProductType $spt) {
                return $spt->code === 'food';
            })->firstOrFail()->id,
            SourceProduct::PARAM_USER_CREATED_ID => $users->firstOrFail()->id,
        ]);

        SourceProduct::create([
            SourceProduct::PARAM_TITLE => 'Пальто демисезонное',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),

            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $sourceProductTypes->filter(function (SourceProductType $spt) {
                return $spt->code === 'outerwear';
            })->firstOrFail()->id,
            SourceProduct::PARAM_USER_CREATED_ID => $users->firstOrFail()->id,
        ]);

        SourceProduct::create([
            SourceProduct::PARAM_TITLE => 'Рубашка льняная с рукавами на лето',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),

            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => $sourceProductTypes->filter(function (SourceProductType $spt) {
                return $spt->code === 'shirts';
            })->firstOrFail()->id,
            SourceProduct::PARAM_USER_CREATED_ID => $users->firstOrFail()->id,
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
