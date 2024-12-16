<?php

namespace App\DTOs;

abstract class BaseDTO
{
    /**
     * Создает новый экземпляр DTO из массива данных.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(...$data);
    }
}
