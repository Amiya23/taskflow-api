<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'project_id' => Project::factory(),

            'title' => fake()->sentence(),

            'description' => fake()->paragraph(),

            'status' => fake()->randomElement([
                'pending',
                'in_progress',
                'completed'
            ]),

            'priority' => fake()->randomElement([
                'low',
                'medium',
                'high'
            ]),

            'due_date' => fake()->date(),
        ];
    }
}