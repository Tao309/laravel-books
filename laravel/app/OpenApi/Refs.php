<?php

namespace App\OpenApi;

final class Refs
{
    // > parameters
    public const string ID = '#/components/parameters/Id';

    public const string IDS = '#/components/parameters/Ids';

    public const string LIMIT = '#/components/parameters/Limit';

    public const string SEARCH = '#/components/parameters/Search';

    public const string BOOK_ID = '#/components/parameters/BookId';

    public const string PRODUCT_ID = '#/components/parameters/ProductId';

    public const string SOURCE_PRODUCT_ID = '#/components/parameters/SourceProductId';
    // < parameters

    // > common
    public const string RESPONSE = '#/components/schemas/Response';

    public const string PAGINATE_RESPONSE = '#/components/schemas/PaginateResponse';
    // < common

    // > authors
    public const string AUTHOR_RESOURCE = '#/components/schemas/AuthorResource';

    public const string AUTHORS_RESOURCE_COLLECTION = '#/components/schemas/AuthorsResourceCollection';

    public const string AUTHORS_PAGINATE_RESOURCE_COLLECTION = '#/components/schemas/AuthorsPaginateResourceCollection';

    public const string AUTHOR_SHOW_RESPONSE = '#/components/schemas/AuthorShowResponse';

    public const string AUTHOR_DESTROY_RESPONSE = '#/components/schemas/AuthorDestroyResponse';

    public const string STORE_AUTHOR_REQUEST = '#/components/schemas/StoreAuthorRequest';

    public const string UPDATE_AUTHOR_REQUEST = '#/components/schemas/UpdateAuthorRequest';
    // < authors

    // > books
    public const string BOOK_RESOURCE = '#/components/schemas/BookResource';

    public const string BOOKS_RESOURCE_COLLECTION = '#/components/schemas/BooksResourceCollection';

    public const string BOOKS_PAGINATE_RESOURCE_COLLECTION = '#/components/schemas/BooksPaginateResourceCollection';

    public const string BOOKS_INDEX_RESPONSE = '#/components/schemas/BooksIndexResponse';

    public const string BOOK_SHOW_RESPONSE = '#/components/schemas/BookShowResponse';

    public const string BOOK_DESTROY_RESPONSE = '#/components/schemas/BookDestroyResponse';

    public const string STORE_BOOK_REQUEST = '#/components/schemas/StoreBookRequest';

    public const string UPDATE_BOOK_REQUEST = '#/components/schemas/UpdateBookRequest';
    // < books

    // > binding_types
    public const string BINDING_TYPE_RESOURCE = '#/components/schemas/BindingTypeResource';

    public const string BINDING_TYPES_RESOURCE_COLLECTION = '#/components/schemas/BindingTypesResourceCollection';

    public const string BINDING_TYPES_INDEX_RESPONSE = '#/components/schemas/BindingTypesIndexResponse';

    public const string BINDING_TYPE_SHOW_RESPONSE = '#/components/schemas/BindingTypeShowResponse';

    public const string BINDING_TYPE_DESTROY_RESPONSE = '#/components/schemas/BindingTypeDestroyResponse';

    public const string STORE_BINDING_TYPE_REQUEST = '#/components/schemas/StoreBindingTypeRequest';

    public const string UPDATE_BINDING_TYPE_REQUEST = '#/components/schemas/UpdateBindingTypeRequest';
    // < binding_types

    // > book_series
    public const string BOOK_SERIES_RESOURCE = '#/components/schemas/BookSeriesResource';

    public const string BOOK_SERIES_RESOURCE_COLLECTION = '#/components/schemas/BookSeriesResourceCollection';

    public const string BOOK_SERIES_INDEX_RESPONSE = '#/components/schemas/BookSeriesIndexResponse';

    public const string BOOK_SERIES_SHOW_RESPONSE = '#/components/schemas/BookSeriesShowResponse';

    public const string BOOK_SERIES_DESTROY_RESPONSE = '#/components/schemas/BookSeriesDestroyResponse';

    public const string STORE_BOOK_SERIES_REQUEST = '#/components/schemas/StoreBookSeriesRequest';

    public const string UPDATE_BOOK_SERIES_REQUEST = '#/components/schemas/UpdateBookSeriesRequest';
    // < book_series

    // > book_user_data
    public const string BOOK_USER_DATA_RESOURCE = '#/components/schemas/BookUserDataResource';

    public const string BOOK_USER_DATA_RESPONSE = '#/components/schemas/BookUserDataResponse';
    // < book_user_data

    // > cities
    public const string CITY_RESOURCE = '#/components/schemas/CityResource';

    public const string CITIES_RESOURCE_COLLECTION = '#/components/schemas/CitiesResourceCollection';

    public const string CITIES_INDEX_RESPONSE = '#/components/schemas/CitiesIndexResponse';

    public const string CITY_SHOW_RESPONSE = '#/components/schemas/CityShowResponse';

    public const string CITY_DESTROY_RESPONSE = '#/components/schemas/CityDestroyResponse';

    public const string STORE_CITY_REQUEST = '#/components/schemas/StoreCityRequest';

    public const string UPDATE_CITY_REQUEST = '#/components/schemas/UpdateCityRequest';
    // < cities

    // > paper_types
    public const string PAPER_TYPE_RESOURCE = '#/components/schemas/PaperTypeResource';

    public const string PAPER_TYPES_RESOURCE_COLLECTION = '#/components/schemas/PaperTypesResourceCollection';

    public const string PAPER_TYPES_INDEX_RESPONSE = '#/components/schemas/PaperTypesIndexResponse';

    public const string PAPER_TYPE_SHOW_RESPONSE = '#/components/schemas/PaperTypeShowResponse';

    public const string PAPER_TYPE_DESTROY_RESPONSE = '#/components/schemas/PaperTypeDestroyResponse';

    public const string STORE_PAPER_TYPE_REQUEST = '#/components/schemas/StorePaperTypeRequest';

    public const string UPDATE_PAPER_TYPE_REQUEST = '#/components/schemas/UpdatePaperTypeRequest';
    // < paper_types

    // > products
    public const string PRODUCT_RESOURCE = '#/components/schemas/ProductResource';

    public const string PRODUCTS_RESOURCE_COLLECTION = '#/components/schemas/ProductsResourceCollection';

    public const string PRODUCTS_PAGINATE_RESOURCE_COLLECTION = '#/components/schemas/ProductsPaginateResourceCollection';

    public const string PRODUCT_SHOW_RESPONSE = '#/components/schemas/ProductShowResponse';

    public const string PRODUCT_DESTROY_RESPONSE = '#/components/schemas/ProductDestroyResponse';

    public const string ARCHIVE_PRODUCT_REQUEST = '#/components/schemas/ArchiveProductRequest';

    public const string MASS_SAVE_PRODUCT_REQUEST = '#/components/schemas/MassSaveProductRequest';

    public const string STORE_PRODUCT_REQUEST = '#/components/schemas/StoreProductRequest';

    public const string UPDATE_PRODUCT_REQUEST = '#/components/schemas/UpdateProductRequest';
    // < products

    // > product_prices
    public const string PRODUCT_PRICE_RESOURCE = '#/components/schemas/ProductPriceResource';
    // < product_prices

    // > product_stocks
    public const string PRODUCT_STOCK_RESOURCE = '#/components/schemas/ProductStockResource';
    // < product_stocks

    // > product_user_data
    public const string PRODUCT_USER_DATA_RESOURCE = '#/components/schemas/ProductUserDataResource';

    public const string PRODUCT_USER_DATA_RESPONSE = '#/components/schemas/ProductUserDataResponse';
    // < product_user_data

    // > publishing_brands
    public const string PUBLISHING_BRAND_RESOURCE = '#/components/schemas/PublishingBrandResource';

    public const string PUBLISHING_BRANDS_RESOURCE_COLLECTION = '#/components/schemas/PublishingBrandsResourceCollection';

    public const string PUBLISHING_BRANDS_INDEX_RESPONSE = '#/components/schemas/PublishingBrandsIndexResponse';

    public const string PUBLISHING_BRAND_SHOW_RESPONSE = '#/components/schemas/PublishingBrandShowResponse';

    public const string PUBLISHING_BRAND_DESTROY_RESPONSE = '#/components/schemas/PublishingBrandDestroyResponse';

    public const string STORE_PUBLISHING_BRAND_REQUEST = '#/components/schemas/StorePublishingBrandRequest';

    public const string UPDATE_PUBLISHING_BRAND_REQUEST = '#/components/schemas/UpdatePublishingBrandRequest';
    // < publishing_brands

    // > publishing_houses
    public const string PUBLISHING_HOUSE_RESOURCE = '#/components/schemas/PublishingHouseResource';

    public const string PUBLISHING_HOUSES_RESOURCE_COLLECTION = '#/components/schemas/PublishingHousesResourceCollection';

    public const string PUBLISHING_HOUSES_INDEX_RESPONSE = '#/components/schemas/PublishingHousesIndexResponse';

    public const string PUBLISHING_HOUSE_SHOW_RESPONSE = '#/components/schemas/PublishingHouseShowResponse';

    public const string PUBLISHING_HOUSE_DESTROY_RESPONSE = '#/components/schemas/PublishingHouseDestroyResponse';

    public const string STORE_PUBLISHING_HOUSE_REQUEST = '#/components/schemas/StorePublishingHouseRequest';

    public const string UPDATE_PUBLISHING_HOUSE_REQUEST = '#/components/schemas/UpdatePublishingHouseRequest';
    // < publishing_houses

    // > shops
    public const string SHOP_RESOURCE = '#/components/schemas/ShopResource';

    public const string SHOPS_RESOURCE_COLLECTION = '#/components/schemas/ShopsResourceCollection';

    public const string SHOPS_INDEX_RESPONSE = '#/components/schemas/ShopsIndexResponse';

    public const string SHOP_SHOW_RESPONSE = '#/components/schemas/ShopShowResponse';
    // < shops

    // > source_products
    public const string SOURCE_PRODUCT_RESOURCE = '#/components/schemas/SourceProductResource';

    public const string SOURCE_PRODUCTS_RESOURCE_COLLECTION = '#/components/schemas/SourceProductsResourceCollection';

    public const string SOURCE_PRODUCTS_INDEX_RESPONSE = '#/components/schemas/SourceProductsIndexResponse';

    public const string SOURCE_PRODUCT_SHOW_RESPONSE = '#/components/schemas/SourceProductShowResponse';

    public const string SOURCE_PRODUCT_DESTROY_RESPONSE = '#/components/schemas/SourceProductDestroyResponse';

    public const string STORE_SOURCE_PRODUCT_REQUEST = '#/components/schemas/StoreSourceProductRequest';

    public const string UPDATE_SOURCE_PRODUCT_REQUEST = '#/components/schemas/UpdateSourceProductRequest';
    // < source_products

    // > source_product_types
    public const string SOURCE_PRODUCT_TYPE_RESOURCE = '#/components/schemas/SourceProductTypeResource';

    public const string SOURCE_PRODUCT_TYPES_RESOURCE_COLLECTION = '#/components/schemas/SourceProductTypesResourceCollection';

    public const string SOURCE_PRODUCT_TYPES_INDEX_RESPONSE = '#/components/schemas/SourceProductTypesIndexResponse';

    public const string SOURCE_PRODUCT_TYPE_SHOW_RESPONSE = '#/components/schemas/SourceProductTypeShowResponse';

    public const string SOURCE_PRODUCT_TYPE_DESTROY_RESPONSE = '#/components/schemas/SourceProductTypeDestroyResponse';

    public const string STORE_SOURCE_PRODUCT_TYPE_REQUEST = '#/components/schemas/StoreSourceProductTypeRequest';

    public const string UPDATE_SOURCE_PRODUCT_TYPE_REQUEST = '#/components/schemas/UpdateSourceProductTypeRequest';
    // < source_product_types

    // > source_product_user_data
    public const string SOURCE_PRODUCT_USER_DATA_RESOURCE = '#/components/schemas/SourceProductUserDataResource';

    public const string SOURCE_PRODUCT_USER_DATA_RESPONSE = '#/components/schemas/SourceProductUserDataResponse';
    // < source_product_user_data

    // > users
    public const string USER_RESOURCE = '#/components/schemas/UserResource';
    // < users

    // > config
    public const string CONFIG_RESOURCE = '#/components/schemas/ConfigResource';

    public const string CONFIG_RESPONSE = '#/components/schemas/ConfigResponse';
    // < config
}
