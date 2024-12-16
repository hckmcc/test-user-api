<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс для валидации параметров запроса списка пользователей.
 * Проверяет параметры пагинации, сортировки и фильтрации.
 */
class IndexRequest extends FormRequest
{
    /**
     * Определяет правила валидации для параметров запроса.
     *
     * @return array<string, string> Массив правил валидации
     */
    public function rules(): array
    {
        return [
            'per_page' => 'nullable|integer|min:1|max:100',
            'sort_field' => 'nullable|string|in:created_at,name',
            'sort_direction' => 'nullable|string|in:asc,desc',
            'name' => 'nullable|string|max:255'
        ];
    }

    /**
     * Получает значения по умолчанию для параметров запроса.
     *
     * @return array<string, mixed>
     */
    public function defaults(): array
    {
        return [
            'per_page' => 15,
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];
    }

    /**
     * Подготавливает валидированные данные для использования.
     *
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();
        $defaults = $this->defaults();

        return [
            'per_page' => $validated['per_page'] ?? $defaults['per_page'],
            'sort_field' => $validated['sort_field'] ?? $defaults['sort_field'],
            'sort_direction' => $validated['sort_direction'] ?? $defaults['sort_direction'],
            'name' => $validated['name'] ?? null
        ];
    }
}
