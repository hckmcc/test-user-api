<?php

namespace App\Services;

use App\DTOs\CreateUserDTO;
use App\DTOs\IndexDTO;
use App\DTOs\UpdateUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

/**
 * Сервис для работы с пользователями.
 * Содержит бизнес-логику обработки данных пользователей.
 */
class UserService
{
    /**
     * Получает список пользователей с пагинацией и сортировкой.
     *
     * @param IndexDTO $dto
     * @return LengthAwarePaginator
     */
    public function getUsers(IndexDTO $dto): LengthAwarePaginator
    {
        try{
            $query = User::query();

            if ($dto->name) {
                $query->where('name', 'like', '%' . $dto->name . '%');
            }

            return $query->orderBy($dto->sortField, $dto->sortDirection)
                ->paginate($dto->perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Создает нового пользователя.
     *
     * @param CreateUserDTO $dto
     * @return User
     */
    public function createUser(CreateUserDTO $dto): User
    {
        try{
            return User::create([
                'name' => $dto->name,
                'password' => Hash::make($dto->password),
                'ip' => $dto->ip,
                'comment' => $dto->comment
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Обновляет данные пользователя.
     *
     * @param User $user
     * @param UpdateUserDTO $dto
     * @return User
     */
    public function updateUser(User $user, UpdateUserDTO $dto): User
    {
        try{
            $data = $dto->toArray();

            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);
            return $user;
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Удаляет пользователя.
     *
     * @param User $user Пользователь
     * @return bool
     */
    public function deleteUser(User $user): bool
    {
        try{
            return $user->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            throw $e;
        }
    }
}
