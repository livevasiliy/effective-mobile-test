<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversMethod(TaskController::class, 'show')]
class GetTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_task_draft_successfully(): void
    {
        // Arrange
        $task = Task::factory()->draft()->create();

        // Act
        $response = $this->getJson('/api/tasks/'.$task->id);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_get_task_in_progress_successfully(): void
    {
        // Arrange
        $task = Task::factory()->inProgress()->create();

        // Act
        $response = $this->getJson('/api/tasks/'.$task->id);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_get_task_completed_successfully(): void
    {
        // Arrange
        $task = Task::factory()->completed()->create();

        // Act
        $response = $this->getJson('/api/tasks/'.$task->id);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_not_found_success(): void
    {
        // Arrange
        $id = fake()->randomNumber();

        // Act
        $response = $this->getJson('/api/tasks/'.$id);

        // Assert
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
