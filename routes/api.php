<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\DashboardController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/projects', [ProjectController::class, 'store']);

    Route::get('/projects', [ProjectController::class, 'index']);

    Route::get('/projects/{project}', [ProjectController::class, 'show']);

    Route::put('/projects/{project}', [ProjectController::class, 'update']);

    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

    Route::post('/tasks', [TaskController::class, 'store']);

    Route::get('/tasks', [TaskController::class, 'index']);

    Route::get('/tasks', [TaskController::class, 'index']);

    Route::post('/tasks', [TaskController::class, 'store']);

    Route::get('/tasks/{task}', [TaskController::class, 'show']);

    Route::put('/tasks/{task}', [TaskController::class, 'update']);

    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
});