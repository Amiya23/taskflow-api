<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProjectRequest;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $projects = Project::query()
        ->where('user_id', auth()->id())
        ->latest()
        ->paginate(10);

    return response()->json([
        'success' => true,
        'data' => ProjectResource::collection($projects),
        'meta' => [
            'current_page' => $projects->currentPage(),
            'last_page' => $projects->lastPage(),
            'per_page' => $projects->perPage(),
            'total' => $projects->total(),
        ]
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request): JsonResponse
{
    $project = Project::create([
        'user_id' => auth()->id(),
        ...$request->validated(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Project created successfully',
        'data' => new ProjectResource($project),
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
{
    $this->authorize('view', $project);

    return response()->json([
        'success' => true,
        'data' => new ProjectResource($project),
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(
    UpdateProjectRequest $request,
    Project $project
)
{
    $this->authorize('update', $project);

    $project->update($request->validated());

    return response()->json([
        'success' => true,
        'message' => 'Project updated successfully',
        'data' => new ProjectResource($project),
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
{
    $this->authorize('delete', $project);

    $project->delete();

    return response()->json([
        'success' => true,
        'message' => 'Project deleted successfully',
    ]);
}


}
