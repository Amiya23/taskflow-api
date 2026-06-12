<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_factory_works(): void
    {
        $project = Project::factory()->create();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
        ]);
    }

    public function test_user_cannot_view_other_user_project(): void
{
    $owner = User::factory()->create();

    $project = Project::factory()->create([
        'user_id' => $owner->id,
    ]);

    $anotherUser = User::factory()->create();

    $token = $anotherUser
        ->createToken('test')
        ->plainTextToken;

    $response = $this
        ->withHeader(
            'Authorization',
            'Bearer '.$token
        )
        ->getJson("/api/projects/{$project->id}");

    $response->assertStatus(403);
}
}