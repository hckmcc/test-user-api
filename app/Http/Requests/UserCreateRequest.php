<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс для валидации запроса на создание нового пользователя.
 */
class UserCreateRequest extends FormRequest
{
    /**
     * Подготавливает данные для валидации.
     * Если IP адрес не указан, берет его из заголовков запроса.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (empty($this->ip)) {
            $this->merge([
                'ip' => $this->ip()
            ]);
        }
    }

    /**
     * Определяет правила валидации для создания пользователя.
     *
     * @return array<string, string> Массив правил валидации
     *
     * Правила:
     * - name: обязательное поле, строка, максимум 255 символов
     * - password: обязательное поле, минимум 8 символов
     * - ip: опциональное поле, должно быть валидным IP-адресом
     *       (если не указан, берется из заголовков запроса)
     * - comment: опциональное поле, строка
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',     // Имя пользователя
            'password' => 'required|min:8',          // Пароль
            'ip' => 'nullable|ip',                   // IP-адрес (опционально)
            'comment' => 'nullable|string'           // Комментарий (опционально)
        ];
    }
}
