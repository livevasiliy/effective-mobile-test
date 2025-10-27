<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversClass(TaskStatus::class)]
#[CoversMethod(TaskStatus::class, 'tasks')]
class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_status_can_be_created(): void
    {
        // Arrange & Act
        $status = TaskStatus::factory()->create(['name' => 'In Progress']);

        // Assert
        $this->assertDatabaseHas('task_statuses', [
            'name' => 'In Progress',
        ]);
    }

    public function test_name_is_required(): void
    {
        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        TaskStatus::factory()->create(['name' => null]);
    }

    public function test_name_must_be_unique(): void
    {
        // Arrange
        TaskStatus::factory()->create(['name' => 'Completed']);

        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        TaskStatus::factory()->create(['name' => 'Completed']);
    }

    public function test_task_status_has_many_tasks(): void
    {
        // Arrange
        $status = TaskStatus::factory()->create();
        $task1 = Task::factory()->create(['status_id' => $status->id]);
        $task2 = Task::factory()->create(['status_id' => $status->id]);

        // Act
        $tasks = $status->tasks;

        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $tasks);
        $this->assertCount(2, $tasks);
        $this->assertTrue($tasks->contains($task1));
        $this->assertTrue($tasks->contains($task2));
    }
}
