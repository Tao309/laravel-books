<?php

use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(SourceProductUserData::TABLE_NAME, function (Blueprint $table) {
            $table->integer(SourceProductUserData::PARAM_LISTEN_PRICE_VALUE)->nullable();
            $table->text(SourceProductUserData::PARAM_COMMENT)->nullable();
            $table->timestampsTz();

            $table->unsignedBigInteger(SourceProductUserData::PARAM_SOURCE_PRODUCT_ID);
            $table->foreign(SourceProductUserData::PARAM_SOURCE_PRODUCT_ID)
                ->references(SourceProduct::PARAM_ID)->on(SourceProduct::TABLE_NAME);

            $table->unsignedBigInteger(SourceProductUserData::PARAM_USER_ID);
            $table->foreign(SourceProductUserData::PARAM_USER_ID)
                ->references(User::PARAM_ID)->on(User::TABLE_NAME);

            $table->primary([SourceProductUserData::PARAM_SOURCE_PRODUCT_ID, SourceProductUserData::PARAM_USER_ID]);
            $table->unique([SourceProductUserData::PARAM_SOURCE_PRODUCT_ID, SourceProductUserData::PARAM_USER_ID]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(SourceProductUserData::TABLE_NAME);
    }
};
