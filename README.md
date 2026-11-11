## Laravel Books

Laravel Books repository

### Действия после установки:

1. `php artisan migrate:fresh --seed` (установка миграций с запуском seeders: UserSeeder, ShopSeeder, CitySeeder, BindingTypeSeeder)
2. `php artisan app:import:run-all --fake=0 --just-check-parsing=0` (импорт данных)

--------
### Swagger API

Swagger документация по api доступна по адресу **/api/documentation/**

[API Docs Json](laravel/storage/api-docs/api-docs.json)

--------
### CLI commands

- Импорт:
  - Запуск всех команд с импортом `php artisan app:import:run-all`
Доступные опции:

| Название | Описание      | Значение по умолчанию |
|----------|----------|-----------------------|
| `fake`     | Фейковый запрос, без записи в БД | Вкл |
| `show-parsing-log`     | Показывать детали парсинга | Вкл |
| `just-check-parsing`     | Проверить только парсинг, без создания новых сущностей | Вкл |
| `import-name`     | Имя команды для импорта | null |

  - Импорт типов бумаги `php artisan app:import:paper_types`
  - Импорт книжных серий `php artisan app:import:books_series`
  - Импорт издательских брэндов `php artisan app:import:publishing_brands`
  - Импорт издательских домов `php artisan app:import:publishing_houses`
  - Импорт авторов `php artisan app:import:authors`
  - Импорт книг `php artisan app:import:books`
  - Импорт BookUserData `php artisan app:import:book_user_data`
  

  - Импорт типов источников товаров `php artisan app:import:source_product_types`
  - Импорт источников товаров `php artisan app:import:source_products`
  - Импорт SourceProductUserData `php artisan app:import:source_product_user_data`


  - Импорт товаров `php artisan app:import:products`
  - Импорт ProductUserData `php artisan app:import:product_user_data`
  - Импорт цен по товарам `php artisan app:import:product_prices`
  - Импорт стоков по товарам `php artisan app:import:product_stocks`

Доступные опции:

| Название | Описание      | Значение по умолчанию |
|----------|----------|-----------------------|
| `fake`     | Фейковый запрос, без записи в БД | Вкл |
| `show-parsing-log`     | Показывать детали парсинга | Вкл |
| `just-check-parsing`     | Проверить только парсинг, без создания новых сущностей | Вкл |

- Создание СQRS компонентов по модели `php artisan app:make:cqrs {modelName}`

--------

### Authors (авторы)

- Добавить через seeder: `php artisan db:seed --class=AuthorSeeder`

### BindingType (тип переплёта)

- Добавить через seeder (добавлен в главный seeder): `php artisan db:seed --class=BindingType`

### City (город)

- Добавить через seeder (добавлен в главный seeder): `php artisan db:seed --class=City`

### Shop (магазин)

- Добавить через seeder (добавлен в главный seeder): `php artisan db:seed --class=Shop`

### PublishingHouse (издательский дом)

- Добавить через seeder: `php artisan db:seed --class=PublishingHouse`

### PublishingBrand (издательский брэнд)

- Добавить через seeder: `php artisan db:seed --class=PublishingBrand`

### BookSeries (книжная серия)

- Добавить через seeder: `php artisan db:seed --class=BookSeries`

### PaperType (тип бумаги)

- Добавить через seeder: `php artisan db:seed --class=PaperTypeSeeder`

### Book (книга)

- Добавить через seeder: `php artisan db:seed --class=BookSeeder`

### SourceProductType (тип источников товара)

- Добавить через seeder: `php artisan db:seed --class=SourceProductTypeSeeder`

### SourceProduct (источники товара)

- Добавить через seeder: `php artisan db:seed --class=SourceProductSeeder`

### Product (товар)

- Добавить через seeder: `php artisan db:seed --class=ProductSeeder`

### BookUserData (данные пользователя по книге)

- Добавление через seeder происходит в BookSeeder

### SourceProductUserData (данные пользователя по источникам товаров)

- Добавление через seeder происходит в SourceProductSeeder

### ProductUserData (данные пользователя по товарам)

- Добавление через seeder происходит в ProductSeeder

### ProductPrice (цены по товарам)

- Добавление через seeder происходит в ProductSeeder

### ProductStock (стоки по товарам)

- Добавление через seeder происходит в ProductSeeder

--------