<?php

namespace Tests\Feature\Database;

use App\Models\Author;
use App\Models\BindingType;
use App\Models\BookSeries;
use App\Models\City;
use App\Models\PublishingHouse;
use App\Models\Shop;
use App\Models\User;
use Database\Seeders\AbstractSeeder;
use Database\Seeders\AuthorSeeder;
use Database\Seeders\BindingTypeSeeder;
use Database\Seeders\BookSeeder;
use Database\Seeders\BookSeriesSeeder;
use Database\Seeders\CitySeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\PaperTypeSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\PublishingBrandSeeder;
use Database\Seeders\PublishingHouseSeeder;
use Database\Seeders\ShopSeeder;
use Database\Seeders\SourceProductSeeder;
use Database\Seeders\SourceProductTypeSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use RuntimeException;
use Tests\TestCase;

class SeederContractTest extends TestCase
{
    use RefreshDatabase;

    public static function seederProvider(): array
    {
        return [
            'abstract' => [AbstractSeeder::class],
            'database' => [DatabaseSeeder::class],
            'binding types' => [BindingTypeSeeder::class],
            'cities' => [CitySeeder::class],
            'shops' => [ShopSeeder::class],
            'users' => [UserSeeder::class],
            'paper types' => [PaperTypeSeeder::class],
            'authors' => [AuthorSeeder::class],
            'book series' => [BookSeriesSeeder::class],
            'publishing brands' => [PublishingBrandSeeder::class],
            'publishing houses' => [PublishingHouseSeeder::class],
            'source product types' => [SourceProductTypeSeeder::class],
            'source products' => [SourceProductSeeder::class],
            'books' => [BookSeeder::class],
            'products' => [ProductSeeder::class],
        ];
    }

    #[DataProvider('seederProvider')]
    public function test_seeder_extends_laravel_seeder_and_is_runnable(string $seederClass): void
    {
        $reflection = new ReflectionClass($seederClass);

        $this->assertTrue($reflection->isSubclassOf(Seeder::class));

        if ($reflection->isAbstract()) {
            return;
        }

        $this->assertTrue($reflection->hasMethod('run'));
        $this->assertTrue($reflection->getMethod('run')->isPublic());
        $this->assertInstanceOf(Seeder::class, $this->app->make($seederClass));
    }

    public function test_all_project_seeder_files_are_covered(): void
    {
        $files = glob(base_path('database/seeders/*.php')) ?: [];
        $covered = array_column(self::seederProvider(), 0);

        $this->assertCount(count($covered), $files);

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $this->assertStringContainsString('namespace Database\Seeders;', $content, "Missing namespace in {$file}");
            preg_match('/(?:abstract\s+)?class\s+(\w+)/', $content, $class);

            $this->assertArrayHasKey(1, $class, "Missing class in {$file}");
            $this->assertContains('Database\\Seeders\\' . $class[1], $covered);
        }
    }

    public function test_database_seeder_calls_only_base_reference_seeders(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount(BindingType::TABLE_NAME, 2);
        $this->assertDatabaseCount(City::TABLE_NAME, 3);
        $this->assertDatabaseCount(Shop::TABLE_NAME, 5);
        $this->assertDatabaseCount(User::TABLE_NAME, 4);

        $this->assertSame(0, Author::query()->count());
        $this->assertSame(0, BookSeries::query()->count());
        $this->assertSame(0, PublishingHouse::query()->count());
    }

    public function test_database_seeder_is_not_idempotent_for_fixed_ids(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->expectException(QueryException::class);

        $this->seed(DatabaseSeeder::class);
    }

    public function test_user_seeder_throws_when_environment_has_no_data(): void
    {
        $keys = [
            'ID_ADMIN', 'NAME_ADMIN', 'TOKEN_ADMIN',
            'ID_USER_1', 'NAME_USER_1', 'TOKEN_USER_1',
            'ID_USER_2', 'NAME_USER_2', 'TOKEN_USER_2',
            'ID_USER_3', 'NAME_USER_3', 'TOKEN_USER_3',
        ];
        $original = [];
        foreach ($keys as $key) {
            $original[$key] = env($key);
            putenv($key . '=');
            $_ENV[$key] = '';
            $_SERVER[$key] = '';
        }

        try {
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('Not found data for seeding');

            $this->seed(UserSeeder::class);
        } finally {
            foreach ($original as $key => $value) {
                if ($value === null) {
                    putenv($key);
                    unset($_ENV[$key], $_SERVER[$key]);
                } else {
                    putenv($key . '=' . $value);
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }
    }
}
