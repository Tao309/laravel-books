<?php

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Author::PIVOT_AUTHOR_BOOKS_TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger(Author::PIVOT_AUTHOR_BOOKS_AUTHOR_ID);
            $table->unsignedBigInteger(Author::PIVOT_AUTHOR_BOOKS_BOOK_ID);

            $table->foreign(Author::PIVOT_AUTHOR_BOOKS_AUTHOR_ID)
                ->references(Author::PARAM_ID)->on(Author::TABLE_NAME)->onDelete('cascade');

            $table->foreign(Author::PIVOT_AUTHOR_BOOKS_BOOK_ID)
                ->references(Book::PARAM_ID)->on(Book::TABLE_NAME)->onDelete('cascade');

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Author::PIVOT_AUTHOR_BOOKS_TABLE);
    }
};
