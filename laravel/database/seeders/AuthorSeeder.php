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
            Author::FIRST_NAME => 'Фёдор',
            Author::LAST_NAME => 'Достоевский',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::FIRST_NAME => 'Рита',
            Author::LAST_NAME => 'Хоффман',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::FIRST_NAME => 'Владимир',
            Author::LAST_NAME => 'Торин',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::FIRST_NAME => 'Крис',
            Author::LAST_NAME => 'Райт',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::FIRST_NAME => 'Майк',
            Author::LAST_NAME => 'Брукс',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table('authors')->insert([
            Author::FIRST_NAME => 'Робби',
            Author::LAST_NAME => 'МакНивен',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);
    }
}
