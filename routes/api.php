<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Группа маршрутов для работы с пользователями.
 * Базовый URL: /api/users
 */
Route::group(['prefix' => 'users'], function () {
    // Получение списка всех пользователей с пагинацией
    // GET /users
    Route::get('/', [UserController::class, 'index']);

    // Создание нового пользователя
    // POST /users
    // Body: name, email, password, ip (optional), comment (optional)
    Route::post('/', [UserController::class, 'create']);

    // Получение данных конкретного пользователя по ID
    // GET /users/{id}
    Route::get('/{id}', [UserController::class, 'show']);

    // Обновление данных пользователя
    // PUT /users/{id}
    // Body: name (optional), password (optional), ip (optional), comment (optional)
    Route::put('/{id}', [UserController::class, 'update']);

    // Удаление пользователя по ID
    // DELETE /users/{id}
    Route::delete('/{id}', [UserController::class, 'destroy']);
});
