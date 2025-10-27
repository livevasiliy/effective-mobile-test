<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversMethod(TaskController::class, 'index')]
class GetAllTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_tasks(): void
    {
        // Arrange
        Task::factory()->draft()->create();
        Task::factory()->inProgress()->create();
        Task::factory()->completed()->create();

        $tasks = Task::with('status')->get()->toArray();

        // Act
        $response = $this->getJson('/api/tasks');

        // Assert
        $response->assertOk();
        $response->assertJson([
            'tasks' => $tasks,
        ]);

        $this->assertCount(count($tasks), $response->json('tasks'));
    }

    public function test_get_empty_list_of_tasks(): void
    {
        // Arrange

        // Act
        $response = $this->getJson('/api/tasks');

        // Assert
        $response->assertOk();
        $response->assertJson(['tasks' => []]);
    }
}
