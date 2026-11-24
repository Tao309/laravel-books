<?php

namespace Tests\Unit\Database;

use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\BookUserData;
use App\Models\City;
use App\Models\PaperType;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductUserData;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ModelFactoriesTest extends TestCase
{
    public static function factoryProvider(): array
    {
        return [
            'author' => [Author::class],
            'binding type' => [BindingType::class],
            'book' => [Book::class],
            'book series' => [BookSeries::class],
            'book user data' => [BookUserData::class],
            'city' => [City::class],
            'paper type' => [PaperType::class],
            'product' => [Product::class],
            'product price' => [ProductPrice::class],
            'product stock' => [ProductStock::class],
            'product user data' => [ProductUserData::class],
            'publishing brand' => [PublishingBrand::class],
            'publishing house' => [PublishingHouse::class],
            'shop' => [Shop::class],
            'source product' => [SourceProduct::class],
            'source product type' => [SourceProductType::class],
            'source product user data' => [SourceProductUserData::class],
            'user' => [User::class],
        ];
    }

    #[DataProvider('factoryProvider')]
    public function test_model_has_factory_with_correct_model(string $modelClass): void
    {
        $factory = $modelClass::factory();

        $this->assertInstanceOf(Factory::class, $factory);
        $this->assertSame($modelClass, $factory->modelName());
        $this->assertNotSame('', $factory->definition()[array_key_first($factory->definition())] ?? '');
    }

    public function test_definitions_fill_model_fillable_attributes(): void
    {
        $models = [
            Author::factory()->make(),
            BindingType::factory()->make(),
            BookSeries::factory()->make(),
            City::factory()->make(),
            PaperType::factory()->make(),
            PublishingBrand::factory()->make(),
            PublishingHouse::factory()->make(),
            Shop::factory()->make(),
            SourceProductType::factory()->make(),
            User::factory()->make(),
        ];

        foreach ($models as $model) {
            $this->assertNotEmpty($model->getAttributes());
        }
    }

    public function test_all_model_factory_classes_are_covered(): void
    {
        $factoryDir = base_path('database/factories');
        $files = glob($factoryDir . '/*Factory.php') ?: [];
        $factoryModels = array_map(
            fn (array $case) => $case[0],
            self::factoryProvider()
        );

        $this->assertCount(count($factoryModels), $files);

        foreach ($files as $file) {
            $content = file_get_contents($file);
            preg_match('/use App\\\\Models\\\\(\w+);/', $content, $matches);
            $this->assertArrayHasKey(1, $matches, "Cannot detect model for {$file}");
            $this->assertContains('App\\Models\\' . $matches[1], $factoryModels);
        }
    }

    public function test_factory_states_are_declared(): void
    {
        $this->assertTrue(method_exists(Shop::factory(), 'ozon'));
        $this->assertTrue(method_exists(Shop::factory(), 'wb'));
        $this->assertTrue(method_exists(Shop::factory(), 'chitaiGorod'));

        $this->assertTrue(method_exists(BindingType::factory(), 'hardcover'));
        $this->assertTrue(method_exists(BindingType::factory(), 'softcover'));

        $this->assertTrue(method_exists(City::factory(), 'krasnodar'));
        $this->assertTrue(method_exists(City::factory(), 'moscow'));
        $this->assertTrue(method_exists(City::factory(), 'spb'));

        $this->assertTrue(method_exists(PaperType::factory(), 'typographic'));
        $this->assertTrue(method_exists(PaperType::factory(), 'offset'));
        $this->assertTrue(method_exists(PaperType::factory(), 'newsprint'));

        $this->assertTrue(method_exists(SourceProductType::factory(), 'shoes'));
        $this->assertTrue(method_exists(SourceProductType::factory(), 'shirts'));
        $this->assertTrue(method_exists(SourceProductType::factory(), 'beverages'));
        $this->assertTrue(method_exists(SourceProductType::factory(), 'medicines'));
        $this->assertTrue(method_exists(SourceProductType::factory(), 'food'));

        $this->assertTrue(method_exists(Product::factory(), 'forBook'));
        $this->assertTrue(method_exists(Product::factory(), 'forSourceProduct'));
        $this->assertTrue(method_exists(Book::factory(), 'withAuthors'));
        $this->assertTrue(method_exists(User::factory(), 'unverified'));
    }
}
