<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\TaskController;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversMethod(TaskController::class, 'store')]
#[CoversClass(StoreTaskRequest::class)]
class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_task_with_all_fields_success(): void
    {
        // Arrange
        $data = Task::factory()->make()->toArray();

        // Act
        $response = $this->postJson('/api/tasks', $data, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Assert
        $response->assertCreated();
    }

    public function test_create_task_with_base_fields_success(): void
    {
        // Arrange
        $data = Task::factory()->basic()->make()->toArray();

        // Act
        $response = $this->postJson('/api/tasks', $data, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Assert
        $response->assertCreated();
    }

    public function test_validation_fields(): void
    {
        // Arrange
        $data = Task::factory()->invalid()->make()->toArray();

        // Act
        $response = $this->postJson('/api/tasks', $data, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'title',
            'status_id',
        ]);

        $response->assertJson([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => [
                'title' => ['The title field is required.'],
                'status_id' => ['The selected status id is invalid.'],
            ],
        ]);
    }

    public function test_validation_max_length_fields(): void
    {
        // Arrange
        $data = Task::factory()->invalidMaxLength()->make()->toArray();

        // Act
        $response = $this->postJson('/api/tasks', $data, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'title',
            'description',
        ]);

        $response->assertJson([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => [
                'title' => ['The title field must not be greater than 255 characters.'],
                'description' => ['The description field must not be greater than 255 characters.'],
            ],
        ]);
    }

    public function test_parent_failed_validation_is_called_when_not_expecting_json(): void
    {
        // Arrange
        $data = [
            'title' => '',
            'description' => 'Some description',
            'status_id' => 999, // Предполагаем, что такой статус не существует
        ];

        // Act
        $response = $this->post('/api/tasks', $data);

        // Assert
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([
            'title' => 'The title field is required.',
            'status_id' => 'The selected status id is invalid.',
        ]);
    }
}
