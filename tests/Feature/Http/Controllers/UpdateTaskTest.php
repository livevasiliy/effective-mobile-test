<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\TaskController;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversMethod(TaskController::class, 'update')]
#[CoversClass(UpdateTaskRequest::class)]
class UpdateTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_task_with_all_fields_success(): void
    {
        // Arrange
        $task = Task::factory()->draft()->create();

        $data = [
            'title' => fake()->name(),
            'description' => fake()->text(),
            'status_id' => TaskStatus::factory()->inProgress()->create()->id,
        ];

        // Act
        $response = $this->putJson('/api/tasks/'.$task->id, $data, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Assert
        $response->assertOk();
        $response->assertJson([
            'task' => [
                'id' => $task->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'status_id' => $data['status_id'],
            ],
        ]);
    }

    public function test_update_task_with_base_fields_success(): void
    {
        // Arrange
        $task = Task::factory()->basic()->draft()->create();
        $data = [
            'title' => fake()->name(),
            'description' => null,
            'status_id' => TaskStatus::factory()->inProgress()->create()->id,
        ];

        // Act
        $response = $this->putJson('/api/tasks/'.$task->id, $data, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Assert
        $response->assertOk();
        $response->assertJson([
            'task' => [
                'id' => $task->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'status_id' => $data['status_id'],
            ],
        ]);
    }

    public function test_validation_fields(): void
    {
        // Arrange
        $data = Task::factory()->invalid()->make()->toArray();
        $task = Task::factory()->draft()->create();

        // Act
        $response = $this->putJson('/api/tasks/'.$task->id, $data, [
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
        $task = Task::factory()->draft()->create();

        // Act
        $response = $this->putJson('/api/tasks/'.$task->id, $data, [
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

    public function test_successfully_not_found(): void
    {
        // Arrange
        $id = fake()->randomNumber();
        $data = Task::factory()->invalidMaxLength()->make()->toArray();

        // Act
        $response = $this->putJson('/api/tasks/'.$id, $data, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Assert
        $response->assertNotFound();
    }

    public function test_parent_failed_validation_is_called_when_not_expecting_json(): void
    {
        // Arrange
        $data = [
            'title' => '',
            'description' => 'Some description',
            'status_id' => 999, // Предполагаем, что такой статус не существует
        ];

        $task = Task::factory()->draft()->create();

        // Act
        $response = $this->put('/api/tasks/'.$task->id, $data);

        // Assert
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([
            'title' => 'The title field is required.',
            'status_id' => 'The selected status id is invalid.',
        ]);
    }
}
