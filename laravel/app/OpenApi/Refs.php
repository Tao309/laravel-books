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
    // < common

    // > books
    public const string BOOK_RESOURCE = '#/components/schemas/BookResource';

    public const string BOOK_DATA_RESOURCE = '#/components/schemas/BookDataResource';

    public const string BOOKS_RESOURCE_COLLECTION = '#/components/schemas/BooksResourceCollection';

    public const string BOOKS_DATA_RESOURCE_COLLECTION = '#/components/schemas/BooksDataResourceCollection';

    public const string BOOKS_PAGINATE_RESOURCE_COLLECTION = '#/components/schemas/BooksPaginateResourceCollection';

    public const string BOOKS_INDEX_RESPONSE = '#/components/schemas/BooksIndexResponse';

    public const string BOOK_DESTROY_RESPONSE = '#/components/schemas/BookDestroyResponse';

    public const string STORE_BOOK_REQUEST = '#/components/schemas/StoreBookRequest';

    public const string UPDATE_BOOK_REQUEST = '#/components/schemas/UpdateBookRequest';
    // < books

    // > authors
    public const string AUTHOR_RESOURCE = '#/components/schemas/AuthorResource';

    public const string AUTHOR_DATA_RESOURCE = '#/components/schemas/AuthorDataResource';

    public const string AUTHORS_RESOURCE_COLLECTION = '#/components/schemas/AuthorsResourceCollection';

    public const string AUTHORS_DATA_RESOURCE_COLLECTION = '#/components/schemas/AuthorsDataResourceCollection';

    public const string AUTHORS_PAGINATE_RESOURCE_COLLECTION = '#/components/schemas/AuthorsPaginateResourceCollection';

    public const string AUTHORS_INDEX_RESPONSE = '#/components/schemas/AuthorsIndexResponse';

    public const string AUTHOR_DESTROY_RESPONSE = '#/components/schemas/AuthorDestroyResponse';

    public const string STORE_AUTHOR_REQUEST = '#/components/schemas/StoreAuthorRequest';

    public const string UPDATE_AUTHOR_REQUEST = '#/components/schemas/UpdateAuthorRequest';
    // < authors

    // > binding_types
    public const string BINDING_TYPE_RESOURCE = '#/components/schemas/BindingTypeResource';

    public const string BINDING_TYPE_DATA_RESOURCE = '#/components/schemas/BindingTypeDataResource';

    public const string BINDING_TYPES_COLLECTION = '#/components/schemas/BindingTypesCollection';

    public const string BINDING_TYPES_DATA_RESOURCE_COLLECTION = '#/components/schemas/BindingTypesDataResourceCollection';

    public const string BINDING_TYPE_DESTROY_RESPONSE = '#/components/schemas/BindingTypeDestroyResponse';

    public const string STORE_BINDING_TYPE_REQUEST = '#/components/schemas/StoreBindingTypeRequest';

    public const string UPDATE_BINDING_TYPE_REQUEST = '#/components/schemas/UpdateBindingTypeRequest';
    // < binding_types

    // > book_series
    public const string BOOK_SERIES_RESOURCE = '#/components/schemas/BookSeriesResource';

    public const string BOOK_SERIES_DATA_RESOURCE = '#/components/schemas/BookSeriesDataResource';

    public const string BOOK_SERIES_RESOURCE_COLLECTION = '#/components/schemas/BookSeriesResourceCollection';

    public const string BOOK_SERIES_DATA_RESOURCE_COLLECTION = '#/components/schemas/BookSeriesDataResourceCollection';

    public const string STORE_BOOK_SERIES_REQUEST = '#/components/schemas/StoreBookSeriesRequest';

    public const string UPDATE_BOOK_SERIES_REQUEST = '#/components/schemas/UpdateBookSeriesRequest';
    // < book_series

    // > book_user_data
    public const string BOOK_USER_DATA_RESOURCE = '#/components/schemas/BookUserDataResource';

    public const string BOOK_USER_DATA_RESPONSE = '#/components/schemas/BookUserDataResponse';
    // < book_user_data

    // > cities
    public const string CITIES_RESOURCE_COLLECTION = '#/components/schemas/CitiesResourceCollection';

    public const string CITIES_DATA_RESOURCE_COLLECTION = '#/components/schemas/CitiesDataResourceCollection';

    public const string CITY_DESTROY_RESPONSE = '#/components/schemas/CityDestroyResponse';

    public const string CITY_RESOURCE = '#/components/schemas/CityResource';

    public const string CITY_DATA_RESOURCE = '#/components/schemas/CityDataResource';

    public const string STORE_CITY_REQUEST = '#/components/schemas/StoreCityRequest';

    public const string UPDATE_CITY_REQUEST = '#/components/schemas/UpdateCityRequest';
    // < cities

    // > paper_types
    public const string PAPER_TYPE_RESOURCE = '#/components/schemas/PaperTypeResource';

    public const string PAPER_TYPE_DATA_RESOURCE = '#/components/schemas/PaperTypeDataResource';

    public const string PAPER_TYPES_RESOURCE_COLLECTION = '#/components/schemas/PaperTypesResourceCollection';

    public const string PAPER_TYPES_DATA_RESOURCE_COLLECTION = '#/components/schemas/PaperTypesDataResourceCollection';

    public const string PAPER_TYPE_DESTROY_RESPONSE = '#/components/schemas/PaperTypeDestroyResponse';

    public const string STORE_PAPER_TYPE_REQUEST = '#/components/schemas/StorePaperTypeRequest';

    public const string UPDATE_PAPER_TYPE_REQUEST = '#/components/schemas/UpdatePaperTypeRequest';
    // < paper_types

    // > products
    public const string PRODUCT_RESOURCE = '#/components/schemas/ProductResource';

    public const string PRODUCT_DATA_RESOURCE = '#/components/schemas/ProductDataResource';

    public const string PRODUCTS_RESOURCE_COLLECTION = '#/components/schemas/ProductsResourceCollection';

    public const string PRODUCTS_DATA_RESOURCE_COLLECTION = '#/components/schemas/ProductsDataResourceCollection';

    public const string PRODUCTS_PAGINATE_RESOURCE_COLLECTION = '#/components/schemas/ProductsPaginateResourceCollection';

    public const string PRODUCTS_INDEX_RESPONSE = '#/components/schemas/ProductsIndexResponse';

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

    public const string PUBLISHING_BRAND_DATA_RESOURCE = '#/components/schemas/PublishingBrandDataResource';

    public const string PUBLISHING_BRANDS_RESOURCE_COLLECTION = '#/components/schemas/PublishingBrandsResourceCollection';

    public const string PUBLISHING_BRANDS_DATA_RESOURCE_COLLECTION = '#/components/schemas/PublishingBrandsDataResourceCollection';

    public const string STORE_PUBLISHING_BRAND_REQUEST = '#/components/schemas/StorePublishingBrandRequest';

    public const string UPDATE_PUBLISHING_BRAND_REQUEST = '#/components/schemas/UpdatePublishingBrandRequest';
    // < publishing_brands

    // > publishing_houses
    public const string PUBLISHING_HOUSE_RESOURCE = '#/components/schemas/PublishingHouseResource';

    public const string PUBLISHING_HOUSE_DATA_RESOURCE = '#/components/schemas/PublishingHouseDataResource';

    public const string PUBLISHING_HOUSES_RESOURCE_COLLECTION = '#/components/schemas/PublishingHousesResourceCollection';

    public const string PUBLISHING_HOUSES_DATA_RESOURCE_COLLECTION = '#/components/schemas/PublishingHousesDataResourceCollection';

    public const string STORE_PUBLISHING_HOUSE_REQUEST = '#/components/schemas/StorePublishingHouseRequest';

    public const string UPDATE_PUBLISHING_HOUSE_REQUEST = '#/components/schemas/UpdatePublishingHouseRequest';
    // < publishing_houses

    // > shops
    public const string SHOP_RESOURCE = '#/components/schemas/ShopResource';

    public const string SHOP_DATA_RESOURCE = '#/components/schemas/ShopDataResource';

    public const string SHOPS_RESOURCE_COLLECTION = '#/components/schemas/ShopsResourceCollection';

    public const string SHOPS_DATA_RESOURCE_COLLECTION = '#/components/schemas/ShopsDataResourceCollection';
    // < shops

    // > source_products
    public const string SOURCE_PRODUCT_RESOURCE = '#/components/schemas/SourceProductResource';

    public const string SOURCE_PRODUCT_DATA_RESOURCE = '#/components/schemas/SourceProductDataResource';

    public const string SOURCE_PRODUCTS_RESOURCE_COLLECTION = '#/components/schemas/SourceProductsResourceCollection';

    public const string SOURCE_PRODUCTS_DATA_RESOURCE_COLLECTION = '#/components/schemas/SourceProductsDataResourceCollection';

    public const string SOURCE_PRODUCT_DESTROY_RESPONSE = '#/components/schemas/SourceProductDestroyResponse';

    public const string STORE_SOURCE_PRODUCT_REQUEST = '#/components/schemas/StoreSourceProductRequest';

    public const string UPDATE_SOURCE_PRODUCT_REQUEST = '#/components/schemas/UpdateSourceProductRequest';
    // < source_products

    // > source_product_types
    public const string SOURCE_PRODUCT_TYPE_RESOURCE = '#/components/schemas/SourceProductTypeResource';

    public const string SOURCE_PRODUCT_TYPE_DATA_RESOURCE = '#/components/schemas/SourceProductTypeDataResource';

    public const string SOURCE_PRODUCT_TYPES_RESOURCE_COLLECTION = '#/components/schemas/SourceProductTypesResourceCollection';

    public const string SOURCE_PRODUCT_TYPES_DATA_RESOURCE_COLLECTION = '#/components/schemas/SourceProductTypesDataResourceCollection';

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
