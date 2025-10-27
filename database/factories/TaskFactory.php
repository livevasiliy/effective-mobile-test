<?php

namespace Database\Factories;

use App\Enums\TaskStatuses;
use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'description' => fake()->sentence(),
            'status_id' => TaskStatus::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function basic(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => fake()->name(),
                'description' => null,
                'status_id' => TaskStatus::factory(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });
    }

    public function invalid(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => null,
                'description' => null,
                'status_id' => fake()->name(),
            ];
        });
    }

    public function invalidMaxLength(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => fake()->realTextBetween(256, 300),
                'description' => fake()->realTextBetween(256, 300),
                'status_id' => TaskStatus::factory(),
            ];
        });
    }

    public function draft(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => TaskStatus::firstOrCreate(['name' => TaskStatuses::DRAFT->value])->id,
            ];
        });
    }

    public function inProgress(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => TaskStatus::firstOrCreate(['name' => TaskStatuses::IN_PROGRESS->value])->id,
            ];
        });
    }

    public function completed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => TaskStatus::firstOrCreate(['name' => TaskStatuses::COMPLETED->value])->id,
            ];
        });
    }
}
