<?php

namespace App\DTOs;

class IndexDTO extends BaseDTO
{
    /**
     * @param int $perPage Количество записей на странице
     * @param string $sortField Поле для сортировки
     * @param string $sortDirection Направление сортировки
     * @param string|null $name Фильтр по имени
     */
    public function __construct(
        public int $perPage,
        public string $sortField,
        public string $sortDirection,
        public ?string $name = null
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
            perPage: $validated['per_page'] ?? 15,
            sortField: $validated['sort_field'] ?? 'created_at',
            sortDirection: $validated['sort_direction'] ?? 'desc',
            name: $validated['name'] ?? null
        );
    }
}
