<?php

use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(ProductUserData::TABLE_NAME, function (Blueprint $table) {
            $table->boolean(ProductUserData::PARAM_AVAILABLE)->default(true);
            $table->timestampTz(ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM)->nullable();
            $table->timestampTz(ProductUserData::PARAM_AVAILABLE_DATE_FROM)->nullable();
            $table->integer(ProductUserData::PARAM_LISTEN_PRICE_VALUE)->nullable();
            $table->integer(ProductUserData::PARAM_LISTEN_QTY_VALUE)->nullable();
            $table->timestampTz(ProductUserData::PARAM_RELEASE_DATE)->nullable();
            $table->boolean(ProductUserData::PARAM_IS_ARCHIVE)->default(false);
            $table->text(ProductUserData::PARAM_COMMENT)->nullable();
            $table->timestampsTz();

            $table->unsignedBigInteger(ProductUserData::PARAM_PRODUCT_ID);
            $table->foreign(ProductUserData::PARAM_PRODUCT_ID)
                ->references(Product::PARAM_ID)->on(Product::TABLE_NAME);

            $table->unsignedBigInteger(ProductUserData::PARAM_USER_ID);
            $table->foreign(ProductUserData::PARAM_USER_ID)
                ->references(User::PARAM_ID)->on(User::TABLE_NAME);

            $table->primary([ProductUserData::PARAM_PRODUCT_ID, ProductUserData::PARAM_USER_ID]);
            $table->unique([ProductUserData::PARAM_PRODUCT_ID, ProductUserData::PARAM_USER_ID]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(ProductUserData::TABLE_NAME);
    }
};
