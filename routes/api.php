<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Search API
Route::get('/search', [\App\Http\Controllers\HomeController::class, 'search']);

// Exercise submission API
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/exercises/{exercise}/submit', [\App\Http\Controllers\ExerciseController::class, 'submit']);
    Route::post('/exercises/{exercise}/progress', [\App\Http\Controllers\ExerciseController::class, 'saveProgress']);
    
    // Video progress API
    Route::post('/videos/{video}/progress', [\App\Http\Controllers\VideoController::class, 'updateProgress']);
    Route::post('/videos/{video}/complete', [\App\Http\Controllers\VideoController::class, 'markCompleted']);
});
