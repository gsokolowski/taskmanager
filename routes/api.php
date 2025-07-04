<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Routes for Authenticaiton
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// Routes protected by Sanctum Authenticaiton
Route::middleware('auth:sanctum')->group(function () { // protect routes with sanctum
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('projects.members', MembersController::class)->only(['index','store','destroy']);
});

// Rroute generated by Laravel
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
