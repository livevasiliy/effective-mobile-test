<?php

namespace Database\Seeders;

use App\Enums\TaskStatuses;
use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statues = array_column(TaskStatuses::cases(), 'value');
        $values = array_map(fn (string $status) => [
            'name' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ], $statues);

        TaskStatus::insert($values);
    }
}
