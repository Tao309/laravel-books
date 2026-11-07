<?php

namespace Database\Seeders;

use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends AbstractSeeder
{
    public function run(): void
    {
        Shop::unguard();

        DB::table(Shop::TABLE_NAME)->insert([
            [
                Shop::PARAM_ID => 1,
                Shop::PARAM_TYPE => 'ozon',
                Shop::PARAM_DOMAIN => 'ozon.ru',
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
            [
                Shop::PARAM_ID => 2,
                Shop::PARAM_TYPE => 'wildberries',
                Shop::PARAM_DOMAIN => 'wildberries.ru',
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
            [
                Shop::PARAM_ID => 3,
                Shop::PARAM_TYPE => 'chitai-gorod',
                Shop::PARAM_DOMAIN => 'chitai-gorod.ru',
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
            [
                Shop::PARAM_ID => 4,
                Shop::PARAM_TYPE => 'knigofan',
                Shop::PARAM_DOMAIN => 'knigofan.ru',
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
            [
                Shop::PARAM_ID => 5,
                Shop::PARAM_TYPE => 'ffan',
                Shop::PARAM_DOMAIN => 'ffan.ru',
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
        ]);

        $this->updateLastId(Shop::TABLE_NAME);
    }
}
