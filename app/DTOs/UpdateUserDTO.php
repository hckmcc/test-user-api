<?php

namespace App\DTOs;

class UpdateUserDTO extends BaseDTO
{
    /**
     * @param string|null $name Имя пользователя
     * @param string|null $password Пароль
     * @param string|null $ip IP адрес
     * @param string|null $comment Комментарий
     */
    public function __construct(
        public ?string $name = null,
        public ?string $password = null,
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
            name: $validated['name'] ?? null,
            password: $validated['password'] ?? null,
            ip: $validated['ip'] ?? null,
            comment: $validated['comment'] ?? null
        );
    }

    /**
     * Преобразует DTO в массив, исключая null значения.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'password' => $this->password,
            'ip' => $this->ip,
            'comment' => $this->comment
        ], fn($value) => !is_null($value));
    }
}
