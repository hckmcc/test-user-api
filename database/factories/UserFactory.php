<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Фабрика для генерации тестовых данных пользователей.
 * Создает случайные данные для заполнения таблицы users.
 */
class UserFactory extends Factory
{
    /**
     * Определяет значения по умолчанию для атрибутов модели User.
     * Использует Faker для генерации случайных данных.
     *
     * @return array<string, mixed> Массив атрибутов пользователя
     *
     * Генерируемые поля:
     * - name: случайное имя
     * - ip: случайный IPv4 адрес
     * - comment: случайное предложение
     * - password: захешированный пароль 'password'
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),      // Генерирует случайное имя
            'ip' => $this->faker->ipv4(),        // Генерирует случайный IPv4
            'comment' => $this->faker->sentence(), // Генерирует случайное предложение
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // захешированный пароль 'password'
        ];
    }
}
