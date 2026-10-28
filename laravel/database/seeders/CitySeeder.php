<?php

namespace Database\Seeders;

use App\Models\City;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cities')->insert([
            City::ID => 1,
            City::CODE => 'КРР',
            City::TITLE => 'Краснодар',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('cities')->insert([
            City::ID => 2,
            City::CODE => 'МОВ',
            City::TITLE => 'Москва',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('cities')->insert([
            City::ID => 3,
            City::CODE => 'СПТ',
            City::TITLE => 'Санкт-Петербург',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);
    }
}
