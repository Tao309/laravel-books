## Laravel Books

Laravel Books repository

### Действия после установки:

1. `php artisan migrate:fresh --seed` (установка миграций с запуском сидеров)
2. `php artisan app:import:run-all --fake=0 --just-check-parsing=0` (импорт данных)

--------
### Swagger API

Swagger документация по api доступна по адресу **/api/documentation/**

--------
### CLI commands

- Запуск всех команд с импортом `php artisan app:import:run-all`
- Импорт издательских домов `php artisan app:import:publishing_houses`
- Импорт издательских брэндов `php artisan app:import:publishing_brands`
- Импорт книжных серий `php artisan app:import:books_series`
- Импорт типов бумаги `php artisan app:import:paper_types`
- Импорт книг `php artisan app:import:books`

Доступные опции:

| Название | Описание      | Значение по умолчанию |
|----------|----------|-----------------------|
| `fake`     | Фейковый запрос, без записи в БД | Вкл |
| `show-parsing-log`     | Показывать детали парсинга | Вкл |
| `just-check-parsing`     | Проверить только парсинг, без создания новых сущностей | Вкл |

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

--------