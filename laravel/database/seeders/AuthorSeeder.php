<?php

namespace Database\Seeders;

use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('authors')->insert([
            Author::PARAM_FIRST_NAME => 'Фёдор',
            Author::PARAM_LAST_NAME => 'Достоевский',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::PARAM_FIRST_NAME => 'Рита',
            Author::PARAM_LAST_NAME => 'Хоффман',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::PARAM_FIRST_NAME => 'Владимир',
            Author::PARAM_LAST_NAME => 'Торин',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::PARAM_FIRST_NAME => 'Крис',
            Author::PARAM_LAST_NAME => 'Райт',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::PARAM_FIRST_NAME => 'Майк',
            Author::PARAM_LAST_NAME => 'Брукс',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::PARAM_FIRST_NAME => 'Робби',
            Author::PARAM_LAST_NAME => 'МакНивен',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::PARAM_FIRST_NAME => 'Стивен',
            Author::PARAM_LAST_NAME => 'Кинг',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::PARAM_FIRST_NAME => 'Катерина',
            Author::PARAM_LAST_NAME => 'Глинистая',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::PARAM_FIRST_NAME => 'Саша',
            Author::PARAM_LAST_NAME => 'Бакушкина',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::PARAM_FIRST_NAME => 'Аметисса',
            Author::PARAM_LAST_NAME => 'Грэсс',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);
    }
}
