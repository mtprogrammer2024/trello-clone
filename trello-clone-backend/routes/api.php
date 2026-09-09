<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\BoardListController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

// روت‌های عمومی
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// روت‌های محافظت‌شده
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Workspaces
    Route::get('/workspaces', [WorkspaceController::class, 'index']);
    Route::post('/workspaces', [WorkspaceController::class, 'store']);
    Route::get('/workspaces/{workspace}', [WorkspaceController::class, 'show']);
    Route::put('/workspaces/{workspace}', [WorkspaceController::class, 'update']);
    Route::delete('/workspaces/{workspace}', [WorkspaceController::class, 'destroy']);

    // Boards
    Route::get('/workspaces/{workspace}/boards', [BoardController::class, 'index']);
    Route::post('/workspaces/{workspace}/boards', [BoardController::class, 'store']);
    Route::get('/boards/{board}', [BoardController::class, 'show']);
    Route::put('/boards/{board}', [BoardController::class, 'update']);
    Route::delete('/boards/{board}', [BoardController::class, 'destroy']);

    // Lists
    Route::post('/boards/{board}/lists', [BoardListController::class, 'store']);
    Route::put('/lists/{list}', [BoardListController::class, 'update']);
    Route::delete('/lists/{list}', [BoardListController::class, 'destroy']);

    // Cards
    Route::post('/lists/{list}/cards', [CardController::class, 'store']);
    Route::get('/cards/{card}', [CardController::class, 'show']);
    Route::put('/cards/{card}', [CardController::class, 'update']);
    Route::patch('/cards/{card}/move', [CardController::class, 'move']);
    Route::delete('/cards/{card}', [CardController::class, 'destroy']);
});