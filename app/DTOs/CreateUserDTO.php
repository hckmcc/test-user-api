<?php

namespace App\DTOs;

class CreateUserDTO extends BaseDTO
{
    /**
     * @param string $name Имя пользователя
     * @param string $password Пароль
     * @param string|null $ip IP адрес
     * @param string|null $comment Комментарий
     */
    public function __construct(
        public string $name,
        public string $password,
        public ?string $ip = null,
        public ?string $comment = null
    ) {}

    /**
     * Создает DTO из валидированного запроса.
     *
     * @param array $validated
     * @return self
     */
    public static function fromRequest(array $validated): self
    {
        return new self(
            name: $validated['name'],
            password: $validated['password'],
            ip: $validated['ip'] ?? null,
            comment: $validated['comment'] ?? null
        );
    }
}
