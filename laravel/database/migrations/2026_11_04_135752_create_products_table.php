<?php

use App\Models\City;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Product::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(Product::PARAM_SHOP_PRODUCT_ID, 30);
            $table->string(Product::PARAM_SHOP_PRODUCT_CODE, 20)->nullable();
            $table->string(Product::PARAM_TITLE, 255);
            $table->timestampsTz();

            $table->nullableMorphs('source');

            $table->unsignedBigInteger(Product::PARAM_SHOP_ID);
            $table->foreign(Product::PARAM_SHOP_ID)->references(Shop::PARAM_ID)->on(Shop::TABLE_NAME)
                ->onDelete('cascade');

            $table->unsignedBigInteger(Product::PARAM_CITY_ID)->nullable();
            $table->foreign(Product::PARAM_CITY_ID)->references(City::PARAM_ID)->on(City::TABLE_NAME)
                ->onDelete('cascade');

            $table->unsignedBigInteger(Product::PARAM_USER_CREATED_ID);
            $table->foreign(Product::PARAM_USER_CREATED_ID)->references(User::PARAM_ID)->on(User::TABLE_NAME)
                ->onDelete('cascade');

            $table->unique([Product::PARAM_SHOP_ID, Product::PARAM_SHOP_PRODUCT_ID]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Product::TABLE_NAME);
    }
};
