# USER API
## Описание
* Проект реализует REST API для управления пользователями с использованием фреймворка Laravel. API предоставляет возможности для получения списка пользователей, получения информации об отдельном пользователе, создания нового пользователя, возможность обновления данных пользователя и удаления пользователя.

## Технологии
* PHP 8.0.30
* Laravel 9.52.18
* MySQL 8
* NGINX
## Что было реализовано
### Сборка окружения
* Dockerfile — Конфигурация для создания контейнера Docker.
* docker-compose.yml — Настройки для запуска контейнеров с помощью Docker Compose.
* .env — Файл с переменными окружения для настройки конфигураций.
* migrations — Миграции для создания таблицы пользователей.
* seeders - Для наполнения таблицы тестовыми данными.
* api.php — Определения маршрутов для API.
* README.md — Описание проекта, инструкция по установке и запуску.
### Реализация логики
* Controllers - Контроллер для обработки запросов пользователей.
* Requests - Для валидации данных.
* Models - Для взаимодействия с БД.
* Services - Для инкапсуляции логики, необходимой для выполнения определенных задач.
### Тестирование
Тестирование включает:

* Тесты контроллера UserController
* Тесты валидации запросов
* Тесты сервисных классов
* Для запуска тестов из контейнера php-fpm используется:
```bash
php artisan test
````
## Запуск приложения
1. Для запуска приложения необходимы Docker и Docker Compose.
2. Клонируйте репозиторий:
```bash
git clone git
```
3.  Настройте файл `.env`:

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=mydb
DB_USERNAME=user
DB_PASSWORD=pass

MYSQL_ROOT_PASSWORD = root_pass
MYSQL_USER=user
MYSQL_PASSWORD=pass
MYSQL_DATABASE=mydb
```
4. Запустите сервер:
```bash
docker compose up --build
```
5. Перейдите в контейнер php-fpm:

```bash
docker compose exec php-fpm bash
 ```

6. Из контейнера php-fpm установите зависимости:

```bash
composer install
  ```

7. Из контейнера php-fpm запустите миграции:

```bash
php artisan migrate
```

8. Из контейнера php-fpm заполните таблицу тестовыми данными с помощью сидера:

```bash
php artisan db:seed
```

9. После запуска сервера можно работать с API через такие сервисы как Postman.

## Эндпоинты
### 1. Получение списка пользователей с пагинацией
- **GET**  `localhost:8082/api/users`
- Параметры:
  - per_page (опционально) - количество записей на странице (по умолчанию 15)
  - sort_field (опционально) - поле для сортировки (name, created_at)
  - sort_direction (опционально) - направление сортировки (asc, desc)
  - name (опционально) - поиск по имени
    **Ответ:**
- **Статус:** `200 OK`
```json
   {
    "data": [
        {
            "id": 25,
            "name": "Jorge Zulauf",
            "ip": "4.35.135.133",
            "comment": "Ut deleniti eum amet id voluptatum fugit sed nostrum.",
            "created_at": "2024-12-16T01:33:57.000000Z",
            "updated_at": "2024-12-16T01:33:57.000000Z"
        },
        {
            "id": 62,
            "name": "John jjj",
            "ip": "192.168.1.2",
            "comment": "new user",
            "created_at": "2024-12-16T01:34:42.000000Z",
            "updated_at": "2024-12-16T01:34:42.000000Z"
        },
        {
            "id": 17,
            "name": "Joana Jenkins II",
            "ip": "74.18.15.46",
            "comment": "Dolor aut corporis officiis.",
            "created_at": "2024-12-16T01:33:57.000000Z",
            "updated_at": "2024-12-16T01:33:57.000000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 10,
        "total": 3
    }
}
```
**Коды состояния**:
- `200 OK`: Данные успешно получены.
### 2. Получение данных конкретного пользователя по ID
- **GET**  `localhost:8082/api/users/{$id}`
  **Ответ:**
- **Статус:** `200 OK`
```json
   {
    "data": {
        "id": 23,
        "name": "Dr. Etha Nader MD",
        "ip": "232.246.158.218",
        "comment": "Vel ipsa suscipit non non et vero molestiae.",
        "created_at": "2024-12-16T01:33:57.000000Z",
        "updated_at": "2024-12-16T01:33:57.000000Z"
    }
}
```
**Коды состояния**:
- `200 OK`: Данные успешно получены.
- `404 Not Found`: Пользователь с указанным ID не найден.

### 3. Создание нового пользователя
- **POST**  `localhost:8082/api/users`
  **Запрос:**
```json
  {
    "name": "John Doe",
    "password": "123testpassword",
    "ip": "192.168.1.2",
    "comment": "new user"
}
```
  **Ответ:**
- **Статус:** `201 Created`
```json
   {
    "data": {
        "name": "John Doe",
        "ip": "192.168.1.2",
        "comment": "new user",
        "updated_at": "2024-12-16T02:09:20.000000Z",
        "created_at": "2024-12-16T02:09:20.000000Z",
        "id": 63
    },
    "message": "User created successfully"
}
```
**Коды состояния**:
- `201 Created`: Пользователь успешно создан.
- `422 Unprocessable Content`: Ошибка валидации данных.
### 4. Обновление данных пользователя
- **PUT**  `localhost:8082/api/users/{$id}`
  **Запрос:**
```json
  {
    "name": "John Doe Updated",
    "comment": "updated user"
}
```
**Ответ:**
- **Статус:** `200 OK`
```json
   {
    "data": {
        "id": 63,
        "name": "John Doe Updated",
        "ip": "192.168.1.2",
        "comment": "updated user",
        "created_at": "2024-12-16T02:09:20.000000Z",
        "updated_at": "2024-12-16T02:14:00.000000Z"
    },
    "message": "User updated successfully"
}
```
**Коды состояния**:
- `200 OK`: Данные пользователя успешно обновлены.
- `404 Not Found`: Пользователь с указанным ID не найден.
- `422 Unprocessable Content`: Ошибка валидации данных.
### 5. Удаление пользователя
- **DELETE**  `localhost:8082/api/users/{$id}`
**Ответ:**
- **Статус:** `200 OK`
```json
  {
    "message": "User deleted successfully"
}
```
**Коды состояния**:
- `200 OK`: Данные пользователя успешно обновлены.
- `404 Not Found`: Пользователь с указанным ID не найден.
