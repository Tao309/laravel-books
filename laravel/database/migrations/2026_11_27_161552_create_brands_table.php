<?php

use App\Enums\BrandCode as BrandEnum;
use App\Models\Brand;
use App\Models\SourceProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Brand::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(Brand::PARAM_CODE, 100);
            $table->string(Brand::PARAM_TITLE, 100);
            $table->timestampsTz();
            $table->unique([Brand::PARAM_CODE]);
        });

        $joyToyBrandId = DB::table(Brand::TABLE_NAME)->insertGetId([
            Brand::PARAM_CODE => BrandEnum::JOYTOY->value,
            Brand::PARAM_TITLE => 'JoyToy',
            Model::CREATED_AT => Carbon::now(),
            Model::UPDATED_AT => Carbon::now(),
        ]);

        DB::table(Brand::TABLE_NAME)->insertOrIgnore(
            [
                [
                    Brand::PARAM_CODE => BrandEnum::XIAOMI->value,
                    Brand::PARAM_TITLE => 'Xiaomi',
                    Model::CREATED_AT => Carbon::now(),
                    Model::UPDATED_AT => Carbon::now(),
                ],
                [
                    Brand::PARAM_CODE => BrandEnum::NECA->value,
                    Brand::PARAM_TITLE => 'Neca',
                    Model::CREATED_AT => Carbon::now(),
                    Model::UPDATED_AT => Carbon::now(),
                ],
                [
                    Brand::PARAM_CODE => BrandEnum::APPLE->value,
                    Brand::PARAM_TITLE => 'Apple',
                    Model::CREATED_AT => Carbon::now(),
                    Model::UPDATED_AT => Carbon::now(),
                ],
            ],
        );

        Schema::table(SourceProduct::TABLE_NAME, function (Blueprint $table) {
            $table->unsignedBigInteger(SourceProduct::PARAM_BRAND_ID)->nullable();
            $table->foreign(SourceProduct::PARAM_BRAND_ID)->references(Brand::PARAM_ID)->on(Brand::TABLE_NAME)
                ->nullOnDelete();
        });

        DB::table(SourceProduct::TABLE_NAME)
            ->whereNull(SourceProduct::PARAM_BRAND_ID)
            ->whereRaw(sprintf('LOWER(%s) LIKE ?', SourceProduct::PARAM_TITLE), ['joytoy%'])
            ->select([SourceProduct::PARAM_ID, SourceProduct::PARAM_TITLE])
            ->chunkById(500, function ($products) use ($joyToyBrandId) {
                foreach ($products as $product) {
                    $title = preg_replace(
                        '/^joytoy\b\s*/iu',
                        '',
                        $product->title
                    );

                    if ($title === $product->title) {
                        continue;
                    }

                    DB::table(SourceProduct::TABLE_NAME)
                        ->where('id', $product->id)
                        ->update([
                            SourceProduct::PARAM_TITLE => $title,
                            SourceProduct::PARAM_BRAND_ID => $joyToyBrandId,
                        ]);
                }
            });
    }

    public function down(): void
    {
        $brandId = DB::table(Brand::TABLE_NAME)
            ->where(Brand::PARAM_CODE, BrandEnum::JOYTOY)
            ->value(Brand::PARAM_ID);

        if ($brandId !== null) {
            DB::table(SourceProduct::TABLE_NAME)
                ->where(SourceProduct::PARAM_BRAND_ID, $brandId)
                ->select([SourceProduct::PARAM_ID, SourceProduct::PARAM_TITLE])
                ->chunkById(500, function ($sourceProducts) {
                    foreach ($sourceProducts as $sourceProduct) {
                        DB::table(SourceProduct::TABLE_NAME)
                            ->where('id', $sourceProduct->id)
                            ->update([
                                SourceProduct::PARAM_TITLE => 'JoyToy ' . $sourceProduct->title,
                            ]);
                    }
                });
        }

        Schema::table(SourceProduct::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn(SourceProduct::PARAM_BRAND_ID);
        });

        Schema::dropIfExists(Brand::TABLE_NAME);
    }
};
