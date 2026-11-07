<?php

namespace Database\Seeders;

use App\Models\City;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CitySeeder extends AbstractSeeder
{
    public function run(): void
    {
        City::unguard();

        DB::table(City::TABLE_NAME)->insert([
            [
                City::PARAM_ID => 1,
                City::PARAM_CODE => 'КРР',
                City::PARAM_TITLE => 'Краснодар',
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
            [
                City::PARAM_ID => 2,
                City::PARAM_CODE => 'МОВ',
                City::PARAM_TITLE => 'Москва',
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
            [
                City::PARAM_ID => 3,
                City::PARAM_CODE => 'СПТ',
                City::PARAM_TITLE => 'Санкт-Петербург',
                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
        ]);

        $this->updateLastId(City::TABLE_NAME);
    }
}
