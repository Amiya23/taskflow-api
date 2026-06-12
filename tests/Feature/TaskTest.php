<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_search_tasks(): void
{
    $user = User::factory()->create();

    Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Authentication Feature',
    ]);

    Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Dashboard Feature',
    ]);

    $token = $user
        ->createToken('test')
        ->plainTextToken;

    $response = $this
        ->withHeader(
            'Authorization',
            'Bearer '.$token
        )
        ->getJson('/api/tasks?search=Authentication');

    $response
        ->assertStatus(200)
        ->assertJsonFragment([
            'title' => 'Authentication Feature',
        ]);
}
}
