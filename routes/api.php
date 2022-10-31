<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('project', [ProjectController::class, 'index']);
    Route::post('project', [ProjectController::class, 'store']);
    Route::get('project/{project}', [ProjectController::class, 'show']);
    Route::patch('project/{project}/name', [ProjectController::class, 'updateName']);
    Route::patch('project/{project}/schema', [ProjectController::class, 'updateSchema']);
    Route::delete('project/{project}', [ProjectController::class, 'delete']);
});
