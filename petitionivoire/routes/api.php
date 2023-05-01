<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PetitionController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\SignatureController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\AuthController;
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






Route::get('petitions', [PetitionController::class, 'index']);
Route::get('petitions/{petition}', [PetitionController::class, 'show']);
Route::get('categories/{category}/petitions', [PetitionController::class, 'getPetitionsByCategory']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('petitions', [PetitionController::class, 'store']);
    Route::put('petitions/{petition}', [PetitionController::class, 'update'])->middleware('can:update,petition');
    Route::delete('petitions/{petition}', [PetitionController::class, 'destroy'])->middleware('can:delete,petition');
    Route::post('petitions/{petition}/sign', [PetitionController::class, 'sign']);
});

Route::post('login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('petitions/{petition}/like', [LikeController::class, 'store']);
    Route::delete('petitions/{petition}/unlike', [LikeController::class, 'destroy']);
    Route::get('petitions/{petition}/likes', [LikeController::class, 'index']);
});

Route::get('petitions/{petition}/comments', [CommentController::class, 'index']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('petitions/{petition}/comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, 'update']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
});

Route::get('categories', [CategoryController::class, 'index']);
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('comments/{comment}/report', [ReportController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('reports', [ReportController::class, 'index']);
});
