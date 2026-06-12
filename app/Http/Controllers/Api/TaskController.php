<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Http\Resources\TaskResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $tasks = Task::query()
        ->where('user_id', auth()->id())

        ->when(
            $request->search,
            fn ($query) => $query->where(function ($q) use ($request) {

                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");

            })
        )

        ->when(
            $request->status,
            fn ($query) => $query->where(
                'status',
                $request->status
            )
        )

        ->when(
            $request->priority,
            fn ($query) => $query->where(
                'priority',
                $request->priority
            )
        )

        ->when(
            $request->sort,
            fn ($query) => $query->orderBy(
                $request->sort
            ),
            fn ($query) => $query->latest()
        )

        ->paginate(10);

    return response()->json([
        'success' => true,
        'data' => TaskResource::collection($tasks),

        'meta' => [
            'current_page' => $tasks->currentPage(),
            'last_page' => $tasks->lastPage(),
            'per_page' => $tasks->perPage(),
            'total' => $tasks->total(),
        ]
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
{
    $project = Project::findOrFail(
        $request->project_id
    );

    if ($project->user_id !== auth()->id()) {

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized project access'
        ], 403);
    }

    $task = Task::create([
        'user_id' => auth()->id(),
        ...$request->validated(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Task created successfully',
        'data' => new TaskResource($task),
    ], 201);
}
    /**
     * Display the specified resource.
     */
    public function show(Task $task)
{
    $this->authorize('view', $task);

    return response()->json([
        'success' => true,
        'data' => new TaskResource($task),
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(
    UpdateTaskRequest $request,
    Task $task
)
{
    $this->authorize('update', $task);

    $task->update(
        $request->validated()
    );

    return response()->json([
        'success' => true,
        'message' => 'Task updated successfully',
        'data' => new TaskResource($task),
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
{
    $this->authorize('delete', $task);

    $task->delete();

    return response()->json([
        'success' => true,
        'message' => 'Task deleted successfully',
    ]);
}
}
