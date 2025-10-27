<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TaskStatuses;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskStatus>
 */
class TaskStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(array_column(TaskStatuses::cases(), 'value')),
        ];
    }

    public function draft(): Factory
    {
        return $this->state(function (array $attributes): array {
            return [
                'name' => TaskStatuses::DRAFT->value,
            ];
        });
    }

    public function inProgress(): Factory
    {
        return $this->state(function (array $attributes): array {
            return [
                'name' => TaskStatuses::IN_PROGRESS->value,
            ];
        });
    }

    public function completed(): Factory
    {
        return $this->state(function (array $attributes): array {
            return [
                'name' => TaskStatuses::COMPLETED->value,
            ];
        });
    }
}
