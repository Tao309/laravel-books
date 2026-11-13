# Laravel Books

API-бэкенд для учёта книг и товаров: каталог книг, товары магазинов, цены,
остатки и пользовательские данные. Данные импортируются из CSV, документация
API генерируется через Swagger/OpenAPI.

- **API:** Laravel 12 (PHP 8.2+)
- **БД:** PostgreSQL 18
- **Кэш:** Redis
- **Инфраструктура:** Docker Compose (nginx + php-fpm + PostgreSQL + Redis)
- **Документация:** Swagger UI — `/api/documentation/`

## Требования

- Docker и Docker Compose (рекомендуемый способ)
- либо локально: PHP 8.2+, Composer 2, PostgreSQL 18, Redis, Node.js 20+ (для Vite)

## Быстрый старт (Docker)

```bash
git clone <repo-url> laravel-books
cd laravel-books

# 1. Переменные для docker-compose (корневой .env)
#    POSTGRES_DB=laravel_books
#    POSTGRES_USER=laravel
#    POSTGRES_PASSWORD=secret

# 2. Конфиг приложения
cp laravel/.env.example laravel/.env

# 3. Запуск контейнеров
docker compose up -d --build

# 4. Установка зависимостей и ключа
docker compose exec php composer install
docker compose exec php php artisan key:generate

# 5. Схема БД + базовые справочники
docker compose exec php php artisan migrate:fresh --seed

# 6. Импорт данных из CSV
docker compose exec php php artisan app:import:run-all --fake=0 --just-check-parsing=0
```

- Приложение: <http://localhost>
- Swagger UI: <http://localhost/api/documentation/>
- API Docs Json [api-docs.json](laravel/storage/api-docs/api-docs.json)

## Переменные окружения

Корневой `.env` (читается `docker-compose.yml`):

| Переменная | Назначение |
|---|---|
| `POSTGRES_DB` | имя базы |
| `POSTGRES_USER` | пользователь БД |
| `POSTGRES_PASSWORD` | пароль БД |

`laravel/.env` — стандартные переменные Laravel плюс собственные:

| Переменная | Назначение |
|---|---|
| `APP_VERSION` | версия приложения, отдаётся в `GET /api/config` |
| `X_REQUESTED_WITH` | обязательный заголовок запроса `x-requested-with` |
| `PRICE_CHECKER_ID` | обязательный заголовок запроса `t-price-checker-id` |
| `URL_WB`, `URL_OZON`, `URL_CHITAI_GOROD`, `URL_FFAN`, `URL_KNIGOFAN` | ссылки магазинов для `GET /api/config` |

> Кастомные переменные отсутствуют в `.env.example` — добавьте их вручную.

## Архитектура

```
Controller → Request (валидация) → DTO → Service → CommandBus → Handler → Repository → Model
                                                                    ↓
                                                          Resource / ResponseContext
```

- `app/CQRS` — команды, запросы, handler'ы и собственный `CommandBus`
  (`CreateBookCommand` → `App\CQRS\Handlers\Books\CreateBookHandler`).
- `app/Services` — диспатчат команды/запросы в шину.
- `app/Repositories` — Eloquent, транзакции, связи, user data.
- `app/DTOs` — `getEntityData()` / `getRelationsData()`.
- `app/Http/QueryFilters` — фильтры `search`, `limit`, `ids`.
- `app/Http/Resources` — API-ресурсы и коллекции.
- `app/Providers/*Provider.php` — связывание handler'ов, репозиториев, сервисов и ресурсов.
- `app/OpenApi` — конфигурации OpenApi.

## API

Все маршруты (кроме документации) защищены middleware `UserSecurity` и требуют
заголовки:

```http
Authorization: Bearer <token>
shop-type: ozon | wildberries | chitai-gorod | ffan | knigofan
x-requested-with: <X_REQUESTED_WITH>
t-price-checker-id: <PRICE_CHECKER_ID>
```

Основные маршруты:

| Метод | Маршрут | Описание |
|---|---|---|
| GET | `/api/config` | настройки, кэш справочников, ссылки магазинов |
| CRUD | `/api/books`, `/api/authors`, `/api/cities`, `/api/paper_types`, `/api/binding_types`, `/api/books_series`, `/api/publishing_brands`, `/api/publishing_houses` | справочники и книги |
| GET | `/api/shops` | список магазинов |
| CRUD | `/api/source_products`, `/api/source_product_types` | источники товаров |
| CRUD | `/api/products` | товары |
| POST | `/api/products/mass-save` | пакетное создание/обновление |
| POST | `/api/products/archive` | архивация товара |
| POST | `/api/products/link_book/{product_id}/{book_id}` | привязать книгу |
| POST | `/api/products/link_source_product/{product_id}/{source_product_id}` | привязать источник |
| POST | `/api/products/unlink/{product_id}` | отвязать источник |
| GET | `/api/{book,source_product,product}_user_data/{id}` | данные пользователя |

Списки поддерживают `ids`, `limit`, `search`. Полная спецификация —
`/api/documentation/` или `laravel/storage/api-docs/api-docs.json`.

## Импорт данных

Каждая команда читает CSV из `laravel/app/Console/Commands/Import/csv/`.

| Команда | CSV |
|---|---|
| `app:import:paper_types` | `paper_types.csv` |
| `app:import:books_series` | `book_series.csv` |
| `app:import:publishing_brands` | `publishing_brands.csv` |
| `app:import:publishing_houses` | `publishing_houses.csv` |
| `app:import:authors` | `authors.csv` |
| `app:import:books` | `books.csv` |
| `app:import:book_user_data` | `book_user_data.csv` |
| `app:import:source_product_types` | `source_product_types.csv` |
| `app:import:source_products` | `source_products.csv` |
| `app:import:source_product_user_data` | `source_product_user_data.csv` |
| `app:import:products` | `products.csv` |
| `app:import:product_user_data` | `product_user_data.csv` |
| `app:import:product_prices` | `product_prices.csv` |
| `app:import:product_stocks` | `product_stocks.csv` |
| `app:import:run-all` | все команды по порядку |

Опции (`run-all` и отдельные команды):

| Опция | По умолчанию | Описание |
|---|---|---|
| `--fake` | `1` | не писать в БД |
| `--show-parsing-log` | `1` | подробный лог парсинга |
| `--just-check-parsing` | `1` | только парсинг, без записи |
| `--import-name` | `null` | только одна команда (для `run-all`) |

Пример «боевого» запуска:

```bash
php artisan app:import:run-all --fake=0 --just-check-parsing=0
php artisan app:import:books --fake=0 --just-check-parsing=0
```

## Генерация CQRS-слоя

```bash
php artisan app:make:cqrs {modelName}
```

Создаёт модель, DTO, репозиторий, сервис, команды/запросы, handler'ы,
контроллер, request'ы и ресурсы по стабам из `app/Console/Commands/stubs`.

## Seeder'ы

`php artisan migrate:fresh --seed` запускает `BindingTypeSeeder`, `CitySeeder`,
`ShopSeeder`, `UserSeeder`. Остальные — точечно:

```bash
php artisan db:seed --class=AuthorSeeder
php artisan db:seed --class=BookSeeder
php artisan db:seed --class=PaperTypeSeeder
php artisan db:seed --class=PublishingHouseSeeder
php artisan db:seed --class=PublishingBrandSeeder
php artisan db:seed --class=BookSeriesSeeder
php artisan db:seed --class=SourceProductTypeSeeder
php artisan db:seed --class=SourceProductSeeder
php artisan db:seed --class=ProductSeeder
```

## Разработка

```bash
make up            # docker compose up -d
make build         # пересобрать контейнеры
make down          # остановить
make php           # shell в php-контейнере
make cs-fix        # Laravel Pint
make test          # PHPUnit
make swagger-generate
```

- Xdebug: `docker compose -f docker-compose.debug.yml up -d --build`
  (порт 9001, serverName `Laravel`, idekey `PHPSTORM`).
- Локально: `cd laravel && composer dev` (serve + queue + pail + vite).

## Структура репозитория

```
laravel-books/
├── laravel/            # приложение Laravel
│   ├── app/CQRS/       # команды, запросы, handler'ы
│   ├── app/Services/   # сервисы
│   ├── app/Repositories/
│   ├── app/Http/       # контроллеры, запросы, ресурсы, фильтры
│   ├── app/Console/Commands/Import/  # импорт CSV
│   └── database/       # миграции и seeder'ы
├── docker/             # php-fpm и nginx
├── docker-compose.yml
└── docker-compose.debug.yml
```

## Лицензия

MIT — см. [LICENSE](LICENSE).