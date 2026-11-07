<?php

use App\Models\Book;
use App\Models\BookUserData;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(BookUserData::TABLE_NAME, function (Blueprint $table) {
            $table->timestampTz(BookUserData::PARAM_RELEASE_DATE)->nullable();
            $table->integer(BookUserData::PARAM_LISTEN_PRICE_VALUE)->nullable();
            $table->text(BookUserData::PARAM_COMMENT)->nullable();
            $table->timestampsTz();

            $table->unsignedBigInteger(BookUserData::PARAM_BOOK_ID);
            $table->foreign(BookUserData::PARAM_BOOK_ID)->references(Book::PARAM_ID)->on(Book::TABLE_NAME);

            $table->unsignedBigInteger(BookUserData::PARAM_USER_ID);
            $table->foreign(BookUserData::PARAM_USER_ID)->references(User::PARAM_ID)->on(User::TABLE_NAME);

            $table->primary([BookUserData::PARAM_BOOK_ID, BookUserData::PARAM_USER_ID]);
            $table->unique([BookUserData::PARAM_BOOK_ID, BookUserData::PARAM_USER_ID]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(BookUserData::TABLE_NAME);
    }
};
