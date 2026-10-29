<?php

namespace Database\Seeders;

use App\Models\BookSeries;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table(BookSeries::TABLE_NAME)->insert([
            BookSeries::NAME => 'Звезды научной фантастики',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table(BookSeries::TABLE_NAME)->insert([
            BookSeries::NAME => 'НоваФикшн. Фантастика: XXI век',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table(BookSeries::TABLE_NAME)->insert([
            BookSeries::NAME => 'Nova Fiction. Русская городская фэнтези',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table(BookSeries::TABLE_NAME)->insert([
            BookSeries::NAME => 'Young Adult. Корейские романы',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table(BookSeries::TABLE_NAME)->insert([
            BookSeries::NAME => 'Red Violet. Темные миры',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);
    }
}
