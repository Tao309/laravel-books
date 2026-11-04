<?php

use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(SourceProduct::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->timestampsTz();

            $table->unsignedBigInteger('source_product_type_id');
            $table->foreign('source_product_type_id')->references(SourceProductType::PARAM_ID)->on(SourceProductType::TABLE_NAME)
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_created_id');
            $table->foreign('user_created_id')->references(User::PARAM_ID)->on(User::TABLE_NAME)
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(SourceProduct::TABLE_NAME);
    }
};
