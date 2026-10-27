## Laravel Books

Laravel Books repository

### Действия после установки:

1. `php artisan migrate:fresh --seed` (установка миграций с запуском сидеров)

--------
### Swagger API

Swagger документация по api доступна по адресу /api/documentation/

--------

### Authors (авторы)

- Добавить авторов через seeder: `php artisan db:seed --class=AuthorSeeder`

### BindingType (тип переплёта)

- Добавить типы переплёта через seeder (добавлен в главный seeder): `php artisan db:seed --class=BindingType`

### City (город)

- Добавить города через seeder (добавлен в главный seeder): `php artisan db:seed --class=City`

--------

### API

#### Authors:

<details>
 <summary><code><span style="color: #00a9b5;font-weight: bold;">GET</span></code> <code><b>/api/authors</b></code> 
<code> (получить авторов, с пагинацией)
</code></summary>

##### Параметры пути:

> Нет

##### Параметры тела запроса:

> Нет

##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "first_name": "Фёдор",
      "last_name": "Достоевский",
      "created_at": "2026-10-26T09:00:19.000000Z",
      "updated_at": "2026-10-26T09:00:19.000000Z"
    },
    {
      "id": 2,
      "first_name": "Рита",
      "last_name": "Хоффман",
      "created_at": "2026-10-26T09:00:19.000000Z",
      "updated_at": "2026-10-26T09:00:19.000000Z"
    }
  ],
  "first_page_url": "http://localhost/api/authors?page=1",
  "from": 1,
  "last_page": 3,
  "last_page_url": "http://localhost/api/authors?page=3",
  "links": [
    {
      "url": null,
      "label": "&laquo; Previous",
      "page": null,
      "active": false
    },
    {
      "url": "http://localhost/api/authors?page=1",
      "label": "1",
      "page": 1,
      "active": true
    }
  ],
  "next_page_url": "http://localhost/api/authors?page=2",
  "path": "http://localhost/api/authors",
  "per_page": 2,
  "prev_page_url": null,
  "to": 2,
  "total": 6
}
```
</details>

<details>
 <summary><code><span style="color: #49cc90;font-weight: bold;">POST</span></code> <code><b>/api/authors</b></code> 
<code> (создать автора)
</code></summary>

##### Параметры пути:

> Нет

##### Параметры тела запроса:

 ```json
{
    "first_name": "Фёдор",
    "last_name": "Достоевский"
}
```

##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
    "data": {
        "id": 11,
        "first_name": "Лев",
        "last_name": "Толстой",
        "updated_at": "2026-10-27 06:32:40",
        "created_at": "2026-10-27 06:32:40"
    }
}
```
</details>

<details>
 <summary><code><span style="color: #61affe;font-weight: bold;">GET</span></code> <code><b>/api/authors/{id}</b></code> 
<code> (получить автора по id)
</code></summary>

##### Параметры пути:

> | Название | Тип      | Тип данных | Описание  |
> |----------|----------|------------|-----------|
> | `id`     | required | int        | ID автора |

##### Параметры тела запроса:
> Нет

##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
   "data": {
        "id": 2,
        "first_name": "Рита",
        "last_name": "Хоффман",
        "updated_at": "2026-10-26 13:19:55",
        "created_at": "2026-10-26 13:19:55"
    }
}
```
</details>

<details>
<summary>
<code><span style="color: #50e3c2;font-weight: bold;">PATCH</span></code>
<code><b>/api/authors/{id}</b></code> 
<code>(обновить автора)</code>
</summary>

##### Параметры пути:

> | Название | Тип      | Тип данных | Описание  |
> |----------|----------|------------|-----------|
> | `id`     | required | int        | ID автора |

##### Параметры тела запроса:
````json
{
    "first_name": "Рита",
    "last_name": "Хоффман"
}
````
##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
   "data": {
        "id": 5,
        "first_name": "Рита",
        "last_name": "Хоффман",
        "updated_at": "2026-10-26 13:19:55",
        "created_at": "2026-10-26 13:19:55"
    }
}
```
</details>

<details>
<summary>
<code><span style="color: #f93e3e;font-weight: bold;">DELETE</span></code>
<code><b>/api/authors/{id}</b></code> 
<code>(удалить автора по ID)</code>
</summary>

##### Параметры пути:

> | Название | Тип      | Тип данных | Описание  |
> |----------|----------|------------|-----------|
> | `id`     | required | int        | ID автора |

##### Параметры тела запроса:
> Нет

##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
    "success": true
}
```
</details>


#### BindingTypes:

<details>
 <summary><code><span style="color: #00a9b5;font-weight: bold;">GET</span></code> 
<code><b>/api/binding_types</b></code> 
<code> (получить типы переплётов)
</code></summary>

##### Параметры пути:

> Нет

##### Параметры тела запроса:

> Нет

##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
    "data": [
        {
            "id": 1,
            "label": "Твёрдый переплёт",
            "updated_at": "2026-10-27 07:41:59",
            "created_at": "2026-10-27 07:41:59"
        },
        {
            "id": 2,
            "label": "Мягкий переплёт",
            "updated_at": "2026-10-27 07:41:59",
            "created_at": "2026-10-27 07:41:59"
        }
    ]
}
```
</details>

<details>
 <summary><code><span style="color: #49cc90;font-weight: bold;">POST</span></code> <code><b>/api/binding_types</b></code> 
<code> (создать тип переплёта)
</code></summary>

##### Параметры пути:

> Нет

##### Параметры тела запроса:

 ```json
{
    "label": "Мягкий переплёт"
}
```

##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
    "data": {
        "id": 5,
        "label": "Мягкий переплёт",
        "updated_at": "2026-10-27 09:01:41",
        "created_at": "2026-10-27 09:01:41"
    }
}
```
</details>

<details>
<summary>
<code><span style="color: #50e3c2;font-weight: bold;">PATCH</span></code>
<code><b>/api/binding_types/{id}</b></code> 
<code>(обновить тип переплёта)</code>
</summary>

##### Параметры пути:

> | Название | Тип      | Тип данных | Описание           |
> |----------|----------|------------|--------------------|
> | `id`     | required | int        | ID типа перепплёта |

##### Параметры тела запроса:
````json
{
    "label": "Твёрдый переплёт"
}
````
##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
    "data": {
        "id": 6,
        "label": "Твёрдый переплёт",
        "updated_at": "2026-10-27 09:01:41",
        "created_at": "2026-10-27 09:01:41"
    }
}
```
</details>

<details>
<summary>
<code><span style="color: #f93e3e;font-weight: bold;">DELETE</span></code>
<code><b>/api/binding_types/{id}</b></code> 
<code>(удалить тип переплёта по ID)</code>
</summary>

##### Параметры пути:

> | Название | Тип      | Тип данных | Описание          |
> |----------|----------|------------|-------------------|
> | `id`     | required | int        | ID типа переплёта |

##### Параметры тела запроса:
> Нет

##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
    "success": true
}
```
</details>


#### Cities:

<details>
<summary>
<code><span style="color: #00a9b5;font-weight: bold;">GET</span></code> 
<code><b>/api/cities</b></code> 
<code> (получить города)
</code></summary>

##### Параметры пути:

> Нет

##### Параметры тела запроса:

> Нет

##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
    "data": [
        {
            "id": 1,
            "code": "КРР",
            "title": "Краснодар",
            "updated_at": "2026-10-27 10:28:50",
            "created_at": "2026-10-27 10:28:50"
        },
        {
            "id": 2,
            "code": "МОВ",
            "title": "Москва",
            "updated_at": "2026-10-27 10:28:50",
            "created_at": "2026-10-27 10:28:50"
        }
    ]
}
```
</details>

<details>
<summary>
<code><span style="color: #49cc90;font-weight: bold;">POST</span></code>
<code><b>/api/cities</b></code> 
<code> (создать город)</code>
</summary>

##### Параметры пути:

> Нет

##### Параметры тела запроса:

 ```json
{
    "code": "МОВ",
    "title": "Москва"
}
```

##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
    "data": {
        "id": 4,
        "code": "МОВ",
        "title": "Москва",
        "updated_at": "2026-10-27 11:02:51",
        "created_at": "2026-10-27 11:02:51"
    }
}
```
</details>

<details>
<summary>
<code><span style="color: #50e3c2;font-weight: bold;">PATCH</span></code>
<code><b>/api/cities/{id}</b></code> 
<code>(обновить город)</code>
</summary>

##### Параметры пути:

> | Название | Тип      | Тип данных | Описание  |
> |----------|----------|------------|-----------|
> | `id`     | required | int        | ID города |

##### Параметры тела запроса:
````json
{
    "code": "СПТ",
    "title": "Санкт-Петербург"
}
````
##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
    "data": {
        "id": 4,
        "code": "СПТ",
        "title": "Санкт-Петербург",
        "updated_at": "2026-10-27 11:02:51",
        "created_at": "2026-10-27 11:02:51"
    }
}
```
</details>

<details>
<summary>
<code><span style="color: #f93e3e;font-weight: bold;">DELETE</span></code>
<code><b>/api/ities/{id}</b></code> 
<code>(удалить город по ID)</code>
</summary>

##### Параметры пути:

> | Название | Тип      | Тип данных | Описание  |
> |----------|----------|------------|-----------|
> | `id`     | required | int        | ID города |

##### Параметры тела запроса:
> Нет

##### Responses:
**Http code**: `200`
**Content-type**: `application/json`

**Response**
```json 
{
    "success": true
}
```
</details>


--------
