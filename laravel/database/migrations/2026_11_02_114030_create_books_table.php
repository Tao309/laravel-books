<?php

use App\Models\BindingType;
use App\Models\BookSeries;
use App\Models\PaperType;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('original_title', 255)->nullable();
            $table->string('isbn', 30)->nullable();
            $table->smallInteger('pages')->nullable();
            $table->integer('circulation')->nullable();
            $table->string('size', 20)->nullable();
            $table->smallInteger('publish_year');
            $table->string('livelib_id')->nullable();
            $table->string('goodreads_id')->nullable();
            $table->string('fantlab_id')->nullable();
            $table->float('livelib_rating')->nullable();
            $table->float('goodreads_rating')->nullable();
            $table->integer('on_sale_count')->default(0);
            $table->timestampsTz();

            $table->unsignedBigInteger('binding_type_id');
            $table->foreign('binding_type_id')->references(BindingType::PARAM_ID)->on(BindingType::TABLE_NAME)
                ->onDelete('cascade');

            $table->unsignedBigInteger('paper_type_id')->nullable();
            $table->foreign('paper_type_id')->references(PaperType::PARAM_ID)->on(PaperType::TABLE_NAME)
                ->onDelete('cascade');

            $table->unsignedBigInteger('publishing_house_id')->nullable();
            $table->foreign('publishing_house_id')->references(PublishingHouse::ID)->on(PublishingHouse::TABLE_NAME)
                ->onDelete('cascade');

            $table->unsignedBigInteger('publishing_brand_id')->nullable();
            $table->foreign('publishing_brand_id')->references(PublishingBrand::ID)->on(PublishingBrand::TABLE_NAME)
                ->onDelete('cascade');

            $table->unsignedBigInteger('book_series_id')->nullable();
            $table->foreign('book_series_id')->references(BookSeries::ID)->on(BookSeries::TABLE_NAME)
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_created_id');
            $table->foreign('user_created_id')->references(User::PARAM_ID)->on(User::TABLE_NAME)
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
