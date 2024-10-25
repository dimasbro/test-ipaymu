<?php

use App\Http\Controllers\ParenthesesController;
use App\Http\Controllers\IntervalsController;
use App\Http\Controllers\ConsecutiveController;
use App\Http\Controllers\RateLimiterController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('validate-parentheses', [ParenthesesController::class, 'validateParentheses']);
Route::post('merge-intervals', [IntervalsController::class, 'mergeIntervals']);
Route::post('longest-consecutive', [ConsecutiveController::class, 'longestConsecutive']);

Route::middleware(['custom_throttle:10,60'])->group(function () {
    Route::get('/rate-limiter', [RateLimiterController::class, 'index']);
});

Route::post('upload', [FileUploadController::class, 'upload']);
Route::get('files/{filename}', [FileUploadController::class, 'download']);
