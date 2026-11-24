<?php

namespace Tests\Feature\Repositories;

use App\CQRS\Queries\Authors\GetAuthorsQuery;
use App\CQRS\Queries\BindingTypes\GetBindingTypesQuery;
use App\CQRS\Queries\BookSeries\GetBookSeriesQuery;
use App\CQRS\Queries\Cities\GetCitiesQuery;
use App\CQRS\Queries\PaperTypes\GetPaperTypesQuery;
use App\CQRS\Queries\PublishingBrands\GetPublishingBrandsQuery;
use App\CQRS\Queries\PublishingHouses\GetPublishingHousesQuery;
use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypesQuery;
use App\DTOs\AuthorDTO;
use App\DTOs\BindingTyperDTO;
use App\DTOs\BookSeriesDTO;
use App\DTOs\CityDTO;
use App\DTOs\CollectionQueryDto;
use App\DTOs\PaperTypeDTO;
use App\DTOs\PublishingBrandDTO;
use App\DTOs\PublishingHouseDTO;
use App\DTOs\SourceProductTypeDTO;
use App\Models\Author;
use App\Models\BindingType;
use App\Models\BookSeries;
use App\Models\City;
use App\Models\PaperType;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Models\SourceProductType;
use App\Repositories\AuthorRepository;
use App\Repositories\BindingTypeRepository;
use App\Repositories\BookSeriesRepository;
use App\Repositories\CityRepository;
use App\Repositories\PaperTypeRepository;
use App\Repositories\PublishingBrandRepository;
use App\Repositories\PublishingHouseRepository;
use App\Repositories\SourceProductTypeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RepositoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public static function repositoryProvider(): array
    {
        $suffix = self::uniqueSuffix();

        return [
            'author' => [
                AuthorRepository::class, Author::class, AuthorDTO::class, GetAuthorsQuery::class,
                [Author::PARAM_FIRST_NAME => 'Repo' . $suffix, Author::PARAM_LAST_NAME => 'Tester'],
            ],
            'binding type' => [
                BindingTypeRepository::class, BindingType::class, BindingTyperDTO::class, GetBindingTypesQuery::class,
                [BindingType::PARAM_LABEL => 'repo-binding-' . $suffix],
            ],
            'book series' => [
                BookSeriesRepository::class, BookSeries::class, BookSeriesDTO::class, GetBookSeriesQuery::class,
                [BookSeries::PARAM_NAME => 'Repo series ' . $suffix],
            ],
            'city' => [
                CityRepository::class, City::class, CityDTO::class, GetCitiesQuery::class,
                [City::PARAM_CODE => substr($suffix, 0, 8), City::PARAM_TITLE => 'Repo city'],
            ],
            'paper type' => [
                PaperTypeRepository::class, PaperType::class, PaperTypeDTO::class, GetPaperTypesQuery::class,
                [PaperType::PARAM_LABEL => 'repo-paper-' . $suffix, PaperType::PARAM_DESCRIPTION => 'Repository test paper'],
            ],
            'publishing brand' => [
                PublishingBrandRepository::class, PublishingBrand::class, PublishingBrandDTO::class, GetPublishingBrandsQuery::class,
                [PublishingBrand::PARAM_NAME => 'Repo brand ' . $suffix],
            ],
            'publishing house' => [
                PublishingHouseRepository::class, PublishingHouse::class, PublishingHouseDTO::class, GetPublishingHousesQuery::class,
                [PublishingHouse::PARAM_NAME => 'Repo house ' . $suffix],
            ],
            'source product type' => [
                SourceProductTypeRepository::class, SourceProductType::class, SourceProductTypeDTO::class, GetSourceProductTypesQuery::class,
                [SourceProductType::PARAM_CODE => 'repo-' . $suffix, SourceProductType::PARAM_NAME => 'Repo type'],
            ],
        ];
    }

    #[DataProvider('repositoryProvider')]
    public function test_repository_supports_full_crud_and_collection_queries(
        string $repositoryClass,
        string $modelClass,
        string $dtoClass,
        string $queryClass,
        array $createData,
    ): void {
        $repository = $this->app->make($repositoryClass);
        $updateData = [];
        foreach ($createData as $key => $value) {
            $updateData[$key] = is_string($value) ? substr($value, 0, 8) . '-U' : $value;
        }

        $entity = $repository->create($dtoClass::fromArray($createData));

        $this->assertInstanceOf($modelClass, $entity);
        $this->assertDatabaseHas($modelClass::TABLE_NAME, $createData);

        $this->assertInstanceOf($modelClass, $repository->get($entity->id));
        $this->assertSame($entity->id, $repository->get($entity->id)->id);

        $all = $repository->getAll(new $queryClass(CollectionQueryDto::fromArray([])));
        $this->assertTrue($all->contains(fn ($item) => $item->id === $entity->id));

        $paginated = $repository->getAllPaginate(new $queryClass(CollectionQueryDto::fromArray([])));
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginated);
        $this->assertGreaterThanOrEqual(1, $paginated->total());

        $updated = $repository->update($entity->id, $dtoClass::fromArray($updateData));
        $this->assertInstanceOf($modelClass, $updated);
        $this->assertDatabaseHas($modelClass::TABLE_NAME, $updateData);

        $this->assertTrue($repository->delete($entity->id));
        $this->assertDatabaseMissing($modelClass::TABLE_NAME, [$modelClass::PARAM_ID => $entity->id]);
    }

    public function test_repository_get_throws_for_missing_entity(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->app->make(AuthorRepository::class)->get(999999);
    }

    private static function uniqueSuffix(): string
    {
        return substr(sha1(uniqid('repository', true)), 0, 12);
    }
}
