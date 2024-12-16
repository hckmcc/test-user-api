<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

/**
 * Сидер для заполнения таблицы пользователей тестовыми данными.
 * Использует фабрику пользователей для генерации случайных записей.
 */
class UserSeeder extends Seeder
{
    /**
     * Запускает процесс заполнения базы данных.
     * Создает 50 тестовых пользователей используя фабрику UserFactory.
     *
     * @return void
     */
    public function run()
    {
        // Создаем 50 случайных пользователей
        User::factory()->count(50)->create();
    }
}
