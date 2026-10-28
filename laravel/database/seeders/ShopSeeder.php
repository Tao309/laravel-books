<?php

namespace Database\Seeders;

use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('shops')->insert([
            Shop::ID => 1,
            Shop::TYPE => 'ozon',
            Shop::DOMAIN => 'ozon.ru',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('shops')->insert([
            Shop::ID => 2,
            Shop::TYPE => 'wildberries',
            Shop::DOMAIN => 'wildberries.ru',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('shops')->insert([
            Shop::ID => 3,
            Shop::TYPE => 'chitai-gorod',
            Shop::DOMAIN => 'chitai-gorod.ru',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('shops')->insert([
            Shop::ID => 4,
            Shop::TYPE => 'knigofan',
            Shop::DOMAIN => 'knigofan.ru',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('shops')->insert([
            Shop::ID => 5,
            Shop::TYPE => 'ffan',
            Shop::DOMAIN => 'ffan.ru',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);
    }
}
