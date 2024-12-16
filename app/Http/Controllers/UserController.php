<?php

namespace App\Http\Controllers;

use App\DTOs\CreateUserDTO;
use App\DTOs\IndexDTO;
use App\DTOs\UpdateUserDTO;
use App\Services\UserService;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\IndexRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер для управления пользователями.
 */
class UserController extends Controller
{
    private UserService $userService;

    /**
     * Внедряем зависимость от сервиса.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Получить список всех пользователей.
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $dto = IndexDTO::fromRequest($request->validated());
        $users = $this->userService->getUsers($dto);

        return response()->json([
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total()
            ]
        ]);
    }

    /**
     * Получить пользователя по ID.
     */
    public function show(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Создать нового пользователя.
     */
    public function create(UserCreateRequest $request): JsonResponse
    {
        $dto = CreateUserDTO::fromRequest($request->validated());
        $user = $this->userService->createUser($dto);

        return response()->json([
            'data' => $user,
            'message' => 'User created successfully'
        ], 201);
    }

    /**
     * Обновить данные пользователя.
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $dto = UpdateUserDTO::fromRequest($request->validated());
        $updatedUser = $this->userService->updateUser($user, $dto);

        return response()->json([
            'data' => $updatedUser,
            'message' => 'User updated successfully'
        ]);
    }

    /**
     * Удалить пользователя.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $this->userService->deleteUser($user);

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
