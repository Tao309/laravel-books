<?php

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(ProductPrice::TABLE_NAME, function (Blueprint $table) {
            $table->unsignedBigInteger(ProductPrice::PARAM_PRODUCT_ID);
            $table->foreign(ProductPrice::PARAM_PRODUCT_ID)
                ->references(Product::PARAM_ID)->on(Product::TABLE_NAME)->cascadeOnDelete();

            $table->unsignedBigInteger(ProductPrice::PARAM_USER_ID);
            $table->foreign(ProductPrice::PARAM_USER_ID)
                ->references(User::PARAM_ID)->on(User::TABLE_NAME)->cascadeOnDelete();

            $table->integer(ProductPrice::PARAM_PRICE)->default(true);
            $table->timestampTz(ProductPrice::PARAM_DATE)->nullable();

            $table->primary([ProductPrice::PARAM_PRODUCT_ID, ProductPrice::PARAM_USER_ID, ProductPrice::PARAM_DATE]);
            $table->unique([ProductPrice::PARAM_PRODUCT_ID, ProductPrice::PARAM_USER_ID, ProductPrice::PARAM_DATE]);
        });

        Schema::create(ProductStock::TABLE_NAME, function (Blueprint $table) {
            $table->unsignedBigInteger(ProductStock::PARAM_PRODUCT_ID);
            $table->foreign(ProductStock::PARAM_PRODUCT_ID)
                ->references(Product::PARAM_ID)->on(Product::TABLE_NAME)->cascadeOnDelete();

            $table->unsignedBigInteger(ProductStock::PARAM_USER_ID);
            $table->foreign(ProductStock::PARAM_USER_ID)
                ->references(User::PARAM_ID)->on(User::TABLE_NAME)->cascadeOnDelete();

            $table->integer(ProductStock::PARAM_QTY)->default(true);
            $table->timestampTz(ProductStock::PARAM_DATE)->nullable();
            $table->json(ProductStock::PARAM_LOG)->nullable();

            $table->primary([ProductStock::PARAM_PRODUCT_ID, ProductStock::PARAM_USER_ID, ProductStock::PARAM_DATE]);
            $table->unique([ProductStock::PARAM_PRODUCT_ID, ProductStock::PARAM_USER_ID, ProductStock::PARAM_DATE]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ProductPrice::TABLE_NAME);
        Schema::dropIfExists(ProductStock::TABLE_NAME);
    }
};
