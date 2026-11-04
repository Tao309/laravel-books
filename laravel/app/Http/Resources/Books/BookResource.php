<?php

namespace App\Http\Resources\Books;

use App\Http\Resources\AbstractResource;
use App\Http\Resources\Authors\AuthorResource;
use App\Http\Resources\BindingTypes\BindingTypeResource;
use App\Http\Resources\BookSeries\BookSeriesResource;
use App\Http\Resources\PaperTypes\PaperTypeResource;
use App\Http\Resources\PublishingBrand\PublishingBrandResource;
use App\Http\Resources\PublishingHouses\PublishingHouseResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Book;
use App\Models\SourceProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookResource',
    properties: [
        new OA\Property(property: Book::PARAM_ID, type: 'integer', example: '1'),
        new OA\Property(property: Book::PARAM_TITLE, type: 'string', example: 'Сияние'),
        new OA\Property(property: Book::PARAM_ORIGINAL_TITLE, type: 'string', example: 'The Shining'),
        new OA\Property(property: Book::PARAM_ISBN, type: 'string', example: '978-5-17-080493-1'),
        new OA\Property(property: Book::PARAM_PAGES, type: 'integer', example: '544'),
        new OA\Property(property: Book::PARAM_CIRCULATION, type: 'integer', example: '21000'),
        new OA\Property(property: Book::PARAM_SIZE, type: 'integer', example: '4x14x21'),
        new OA\Property(property: Book::PARAM_PUBLISH_YEAR, type: 'integer', example: '2025'),
        new OA\Property(property: Book::PARAM_LIVELIB_ID, type: 'integer', example: '1000965735'),
        new OA\Property(property: Book::PARAM_GOODREADS_ID, type: 'integer', example: '11588'),
        new OA\Property(property: Book::PARAM_FANTLAB_ID, type: 'integer', example: 'edition115331'),
        new OA\Property(property: Book::PARAM_LIVELIB_RATING, type: 'float', example: '4.4'),
        new OA\Property(property: Book::PARAM_GOODREADS_RATING, type: 'float', example: '4.28'),
        new OA\Property(property: 'updated_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: 'created_at', type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(
            property: Book::PARAM_BINDING_TYPE,
            ref: '#/components/schemas/BindingTypeResource',
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_PAPER_TYPE,
            ref: '#/components/schemas/PaperTypeResource',
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_AUTHORS,
            type: 'array',
            items: new OA\Items(
                ref: '#/components/schemas/AuthorResource',
                type: 'object'
            )
        ),
        new OA\Property(
            property: Book::PARAM_PUBLISHING_HOUSE,
            ref: '#/components/schemas/PublishingHouseResource',
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_PUBLISHING_BRAND,
            ref: '#/components/schemas/PublishingBrandResource',
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_BOOK_SERIES,
            ref: '#/components/schemas/BookSeriesResource',
            type: 'object'
        ),
        new OA\Property(
            property: SourceProduct::PARAM_USER_CREATED,
            ref: '#/components/schemas/UserResource',
            type: 'object'
        ),
    ],
    type: 'object',
)]
#[OA\Schema(
    schema: 'BookDataResource',
    properties: [
        new OA\Property(
            property: 'data',
            ref: '#/components/schemas/BookResource',
            type: 'object'
        ),
    ],
    type: 'object'
)]
class BookResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            Book::PARAM_ID => $this->id,
            Book::PARAM_TITLE => $this->offsetGet(Book::PARAM_TITLE),
            Book::PARAM_ORIGINAL_TITLE => $this->offsetGet(Book::PARAM_ORIGINAL_TITLE),
            Book::PARAM_ISBN => $this->offsetGet(Book::PARAM_ISBN),
            Book::PARAM_PAGES => $this->offsetGet(Book::PARAM_PAGES),
            Book::PARAM_CIRCULATION => $this->offsetGet(Book::PARAM_CIRCULATION),
            Book::PARAM_SIZE => $this->offsetGet(Book::PARAM_SIZE),
            Book::PARAM_PUBLISH_YEAR => $this->offsetGet(Book::PARAM_PUBLISH_YEAR),
            Book::PARAM_LIVELIB_ID => $this->offsetGet(Book::PARAM_LIVELIB_ID),
            Book::PARAM_GOODREADS_ID => $this->offsetGet(Book::PARAM_GOODREADS_ID),
            Book::PARAM_FANTLAB_ID => $this->offsetGet(Book::PARAM_FANTLAB_ID),
            Book::PARAM_LIVELIB_RATING => $this->offsetGet(Book::PARAM_LIVELIB_RATING),
            Book::PARAM_GOODREADS_RATING => $this->offsetGet(Book::PARAM_GOODREADS_RATING),
            Model::UPDATED_AT => Carbon::createFromDate($this->updated_at)->format('Y-m-d H:i:s'),
            Model::CREATED_AT => Carbon::createFromDate($this->created_at)->format('Y-m-d H:i:s'),
            // > relations
            Book::PARAM_AUTHORS => AuthorResource::collection($this->authors),
            Book::PARAM_BINDING_TYPE => BindingTypeResource::make($this->bindingType),
            Book::PARAM_PAPER_TYPE => PaperTypeResource::make($this->paperType),
            Book::PARAM_PUBLISHING_HOUSE => PublishingHouseResource::make($this->publishingHouse),
            Book::PARAM_PUBLISHING_BRAND => PublishingBrandResource::make($this->publishingBrand),
            Book::PARAM_BOOK_SERIES => BookSeriesResource::make($this->bookSeries),
            Book::PARAM_USER_CREATED => UserResource::make($this->userCreated),
            // < relations
        ];
    }
}
