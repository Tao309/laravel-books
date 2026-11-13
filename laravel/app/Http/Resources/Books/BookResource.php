<?php

namespace App\Http\Resources\Books;

use App\Http\Resources\AbstractResource;
use App\Http\Resources\Authors\AuthorResource;
use App\Http\Resources\BindingTypes\BindingTypeResource;
use App\Http\Resources\BookSeries\BookSeriesResource;
use App\Http\Resources\BookUserDatas\BookUserDataResource;
use App\Http\Resources\PaperTypes\PaperTypeResource;
use App\Http\Resources\PublishingBrand\PublishingBrandResource;
use App\Http\Resources\PublishingHouses\PublishingHouseResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Book;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BookResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            Book::PARAM_ID => $this->offsetGet(Book::PARAM_ID),
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
            Model::UPDATED_AT => DateService::createFromDate($this->updated_at),
            Model::CREATED_AT => DateService::createFromDate($this->created_at),
            // > relations
            Book::PARAM_AUTHORS => $this->whenLoaded(
                'authors',
                fn () => AuthorResource::collection($this->authors)
            ),
            Book::PARAM_BINDING_TYPE => $this->whenLoaded(
                'bindingType',
                fn () => BindingTypeResource::make($this->bindingType)
            ),
            Book::PARAM_PAPER_TYPE => $this->whenLoaded(
                'paperType',
                fn () => PaperTypeResource::make($this->paperType)
            ),
            Book::PARAM_PUBLISHING_HOUSE => $this->whenLoaded(
                'publishingHouse',
                fn () => PublishingHouseResource::make($this->publishingHouse)
            ),
            Book::PARAM_PUBLISHING_BRAND => $this->whenLoaded(
                'publishingBrand',
                fn () => PublishingBrandResource::make($this->publishingBrand)
            ),
            Book::PARAM_BOOK_SERIES => $this->whenLoaded(
                'bookSeries',
                fn () => BookSeriesResource::make($this->bookSeries)
            ),
            Book::PARAM_USER_CREATED => $this->whenLoaded(
                'userCreated',
                fn () => UserResource::make($this->userCreated)
            ),
            Book::PARAM_BOOK_USER_DATA => $this->whenLoaded(
                'bookUserData',
                fn () => BookUserDataResource::make($this->bookUserData)
            ),
            // < relations
        ];
    }
}
