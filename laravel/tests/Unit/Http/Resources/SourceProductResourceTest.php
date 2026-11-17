<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\SourceProducts\SourceProductResource;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * resolve() вызывается с явным Request, чтобы не дергать контейнер
 * (PHPUnit\Framework\TestCase без Laravel). Связи задаются вручную
 * через setRelation — поведение whenLoaded проверяется в обоих режимах.
 */
class SourceProductResourceTest extends TestCase
{
    public function test_resolves_scalar_fields_when_relations_not_loaded(): void
    {
        $sourceProduct = $this->sourceProduct();

        $data = SourceProductResource::make($sourceProduct)->resolve(new Request);

        $this->assertSame(5, $data['id']);
        $this->assertSame('Кроссовки лето', $data['title']);
        $this->assertSame('2026-11-10 12:00:00', $data['updated_at']);
        $this->assertSame('2026-11-11 13:30:00', $data['created_at']);

        // whenLoaded без загруженной связи возвращает MissingValue:
        // resolve() вырезает такие ключи из payload целиком (не null)
        $this->assertArrayNotHasKey(SourceProduct::PARAM_SOURCE_PRODUCT_TYPE, $data);
        $this->assertArrayNotHasKey(SourceProduct::PARAM_USER_CREATED, $data);
        $this->assertArrayNotHasKey(SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA, $data);
    }

    public function test_resolves_loaded_relations_as_nested_resources(): void
    {
        $sourceProduct = $this->sourceProduct();

        $type = new SourceProductType;
        $type->id = 9;
        $type->code = 'shoes';
        $type->name = 'Обувь';

        $user = new User;
        $user->id = 3;
        $user->name = 'Пользователь';
        $user->email = 'user@example.com';

        $userData = new SourceProductUserData;
        $userData->listen_price_value = 1500;
        $userData->comment = 'Нужен размер 43';

        $sourceProduct
            ->setRelation('sourceProductType', $type)
            ->setRelation('userCreated', $user)
            ->setRelation('sourceProductUserData', $userData);

        $data = SourceProductResource::make($sourceProduct)->resolve(new Request);

        $this->assertSame(9, $data[SourceProduct::PARAM_SOURCE_PRODUCT_TYPE]['id']);
        $this->assertSame('shoes', $data[SourceProduct::PARAM_SOURCE_PRODUCT_TYPE]['code']);
        $this->assertSame(3, $data[SourceProduct::PARAM_USER_CREATED]['id']);
        $this->assertSame('Пользователь', $data[SourceProduct::PARAM_USER_CREATED]['name']);
        $this->assertSame(1500, $data[SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA]['listen_price_value']);
        $this->assertSame('Нужен размер 43', $data[SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA]['comment']);
    }

    public function test_timestamps_are_null_for_unpersisted_model(): void
    {
        $sourceProduct = new SourceProduct;
        $sourceProduct->id = 5;
        $sourceProduct->title = 'Кроссовки лето';

        $data = SourceProductResource::make($sourceProduct)->resolve(new Request);

        $this->assertNull($data['created_at']);
        $this->assertNull($data['updated_at']);
    }

    private function sourceProduct(): SourceProduct
    {
        $sourceProduct = new SourceProduct;
        $sourceProduct->id = 5;
        $sourceProduct->title = 'Кроссовки лето';
        $sourceProduct->created_at = Carbon::create(2026, 11, 11, 13, 30, 0);
        $sourceProduct->updated_at = Carbon::create(2026, 11, 10, 12, 0, 0);

        return $sourceProduct;
    }
}
