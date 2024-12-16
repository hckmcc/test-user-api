<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс для валидации запроса на обновление данных пользователя.
 */
class UserUpdateRequest extends FormRequest
{
    /**
     * Определяет правила валидации для обновления пользователя.
     *
     * @return array<string, string> Массив правил валидации
     *
     * Правила:
     * - name: опционально, если передано - обязательное, строка, максимум 255 символов
     * - password: опционально, если передано - обязательное, минимум 8 символов
     * - ip: опциональное поле, должно быть валидным IP-адресом
     * - comment: опциональное поле, строка
     *
     * Правило 'sometimes' означает, что поле будет проверяться только если оно присутствует в запросе.
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',  // Имя пользователя (если передано)
            'password' => 'sometimes|required|min:8',       // Пароль (если передан)
            'ip' => 'nullable|ip',                         // IP-адрес (опционально)
            'comment' => 'nullable|string'                 // Комментарий (опционально)
        ];
    }
}
