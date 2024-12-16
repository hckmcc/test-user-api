<?php

namespace Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тесты для API маршрутов пользователей.
 */
class UserRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест получения списка пользователей.
     */
    public function test_can_get_users_list(): void
    {
        // Создаем тестовых пользователей
        User::factory()->count(3)->create();

        // Выполняем GET запрос
        $response = $this->getJson('/api/users');

        // Проверяем ответ
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'ip', 'comment', 'created_at', 'updated_at']
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total']
            ]);
    }

    /**
     * Тест поиска и сортировки пользователей.
     */
    public function test_can_find_and_sort_users(): void
    {
        // Создаем пользователей с разными именами
        User::factory()->create(['name' => 'Johnny']);
        User::factory()->create(['name' => 'Johnathan']);
        User::factory()->create(['name' => 'Polly']);

        // Тестируем поиск по имени
        $response = $this->getJson('/api/users?name=John&sort_field=name&sort_direction=asc');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', 'Johnathan');
    }

    /**
     * Тест создания нового пользователя.
     */
    public function test_can_create_user(): void
    {
        $userData = [
            'name' => 'Test User',
            'password' => 'password123',
            'ip' => '192.168.1.1',
            'comment' => 'Test comment'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'ip', 'comment', 'created_at', 'updated_at'],
                'message'
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'ip' => '192.168.1.1',
            'comment' => 'Test comment'
        ]);
    }

    /**
     * Тест валидации при создании пользователя.
     */
    public function test_validation_when_creating_user(): void
    {
        $response = $this->postJson('/api/users', [
            'name' => '',
            'password' => '123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'password']);
    }

    /**
     * Тест получения конкретного пользователя.
     */
    public function test_can_get_single_user(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'ip', 'comment', 'created_at', 'updated_at']
            ]);
    }

    /**
     * Тест обновления пользователя.
     */
    public function test_can_update_user(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'comment' => 'Updated comment'
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Name')
            ->assertJsonPath('data.comment', 'Updated comment');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'comment' => 'Updated comment'
        ]);
    }

    /**
     * Тест удаления пользователя.
     */
    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'User deleted successfully']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * Тест обработки несуществующего пользователя.
     */
    public function test_returns_404_for_non_existent_user(): void
    {
        $response = $this->getJson("/api/users/999");
        $response->assertStatus(404);

        $response = $this->putJson("/api/users/999", ['name' => 'Test']);
        $response->assertStatus(404);

        $response = $this->deleteJson("/api/users/999");
        $response->assertStatus(404);
    }

    /**
     * Тест автоматического заполнения IP адреса.
     */
    public function test_ip_is_automatically_filled(): void
    {
        $userData = [
            'name' => 'Test User',
            'password' => 'password123',
            'comment' => 'Test comment'
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201);
        $this->assertNotNull($response->json('data.ip'));
    }
}
