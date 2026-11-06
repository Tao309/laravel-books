<?php

namespace Database\Seeders;

use App\Models\BookUserData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookUserDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::table(BookUserData::TABLE_NAME)->insert([
            [
                BookUserData::PARAM_BOOK_ID => 1,
                BookUserData::PARAM_USER_ID => 2,
                BookUserData::PARAM_RELEASE_DATE => Carbon::now()->addDays(10),
                BookUserData::PARAM_LISTEN_PRICE_VALUE => 760,
                BookUserData::PARAM_COMMENT => 'Подождать отзывы',

                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
            [
                BookUserData::PARAM_BOOK_ID => 2,
                BookUserData::PARAM_USER_ID => 2,
                BookUserData::PARAM_RELEASE_DATE => Carbon::now()->addDays(14),
                BookUserData::PARAM_LISTEN_PRICE_VALUE => 500,
                BookUserData::PARAM_COMMENT => 'Выше 500 рублей не брать',

                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
            [
                BookUserData::PARAM_BOOK_ID => 1,
                BookUserData::PARAM_USER_ID => 3,
                BookUserData::PARAM_RELEASE_DATE => Carbon::now()->addDays(14),
                BookUserData::PARAM_LISTEN_PRICE_VALUE => 999,
                BookUserData::PARAM_COMMENT => 'После акции',

                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
            [
                BookUserData::PARAM_BOOK_ID => 3,
                BookUserData::PARAM_USER_ID => 2,
                BookUserData::PARAM_RELEASE_DATE => null,
                BookUserData::PARAM_LISTEN_PRICE_VALUE => null,
                BookUserData::PARAM_COMMENT => '',

                Model::CREATED_AT => Carbon::now(),
                Model::UPDATED_AT => Carbon::now(),
            ],
        ]);
    }
}
