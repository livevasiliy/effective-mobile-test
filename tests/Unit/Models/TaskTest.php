<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Tests\TestCase;

#[CoversClass(Task::class)]
#[CoversMethod(Task::class, 'status')]
class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_can_be_created(): void
    {
        // Arrange
        $status = TaskStatus::factory()->create();

        // Act
        $task = Task::factory()->create([
            'title' => 'Sample Task',
            'description' => 'This is a sample task.',
            'status_id' => $status->id,
        ]);

        // Assert
        $this->assertDatabaseHas('tasks', [
            'title' => 'Sample Task',
            'description' => 'This is a sample task.',
            'status_id' => $status->id,
        ]);
    }

    public function test_task_belongs_to_task_status(): void
    {
        // Arrange
        $status = TaskStatus::factory()->create();
        $task = Task::factory()->create(['status_id' => $status->id]);

        // Act & Assert
        $this->assertInstanceOf(TaskStatus::class, $task->status);
        $this->assertEquals($status->id, $task->status->id);
    }

    public function test_title_is_required(): void
    {
        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Task::factory()->create(['title' => null]);
    }

    public function test_status_id_is_required(): void
    {
        // Act & Assert
        $this->expectException(\Illuminate\Database\QueryException::class);
        Task::factory()->create(['status_id' => null]);
    }
}
