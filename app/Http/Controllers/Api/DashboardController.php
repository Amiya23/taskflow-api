<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        return response()->json([
            'success' => true,
            'data' => [

                'total_projects' => Project::where(
                    'user_id',
                    $userId
                )->count(),

                'total_tasks' => Task::where(
                    'user_id',
                    $userId
                )->count(),

                'pending_tasks' => Task::where(
                    'user_id',
                    $userId
                )
                ->where('status', 'pending')
                ->count(),

                'completed_tasks' => Task::where(
                    'user_id',
                    $userId
                )
                ->where('status', 'completed')
                ->count(),

            ]
        ]);
    }
}