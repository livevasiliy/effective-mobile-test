<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversMethod(TaskController::class, 'destroy')]
class DeleteTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_successfully_deleted_task(): void
    {
        // Arrange
        $task = Task::factory()->create();

        // Act
        $response = $this->deleteJson('/api/tasks/'.$task->id);

        // Assert
        $response->assertNoContent();
    }

    public function test_successfully_not_found(): void
    {
        // Arrange
        $id = fake()->randomNumber();

        // Act
        $response = $this->deleteJson('/api/tasks/'.$id);

        // Assert
        $response->assertNotFound();
    }
}
