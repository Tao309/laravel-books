<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(Product::TABLE_NAME, function (Blueprint $table) {
            $table->index(
                [Product::PARAM_ID, Product::PARAM_SHOP_ID],
                'products_id_shop_id_idx'
            );
            $table->index(
                [Product::PARAM_SHOP_PRODUCT_ID, Product::PARAM_SHOP_ID],
                'products_shop_product_id_shop_id_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table(Product::TABLE_NAME, function (Blueprint $table) {
            $table->dropIndex('products_shop_product_id_shop_id_idx');
            $table->dropIndex('products_id_shop_id_idx');
        });
    }
};
