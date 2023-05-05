<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PetitionController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\TopicController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);

Route::get('petitions', [PetitionController::class, 'index']);
Route::get('petitions/{petition}', [PetitionController::class, 'show']);
Route::get('categories/{category}/petitions', [PetitionController::class, 'getPetitionsByCategory']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('users/{user}/petitions', [UserController::class, 'userPetitions']);
    Route::post('petitions', [PetitionController::class, 'store']);
    Route::put('petitions/{petition}', [PetitionController::class, 'update'])->middleware('can:update,petition');
    Route::delete('petitions/{petition}', [PetitionController::class, 'destroy'])->middleware('can:delete,petition');
    Route::post('petitions/{petition}/sign', [PetitionController::class, 'sign']);
    Route::post('{type}/{id}/like', [LikeController::class, 'store']);
    Route::delete('{type}/{id}/unlike', [LikeController::class, 'destroy']);
    Route::get('{type}/{id}/likes', [LikeController::class, 'index']);
    Route::post('comments/{comment}/report', [ReportController::class, 'store']);
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
    Route::get('reports', [ReportController::class, 'index']);

    Route::get('admin/dashboard-stats', [AdminController::class, 'dashboardStats']);
    Route::post('admin/petitions/{petition}/approve', [AdminController::class, 'approvePetition']);
    Route::post('admin/petitions/{petition}/reject', [AdminController::class, 'rejectPetition']);
    Route::get('admin/categories', [AdminController::class, 'categories']);
    Route::post('admin/categories', [AdminController::class, 'storeCategory']);
    Route::put('admin/categories/{category}', [AdminController::class, 'updateCategory']);
    Route::delete('admin/categories/{category}', [AdminController::class, 'deleteCategory']);
    Route::get('admin/reported-comments', [AdminController::class, 'reportedComments']);
    Route::post('admin/reported-comments/{comment}/resolve', [AdminController::class, 'resolveReportedComment']);
});

// Routes pour les sujets (topics)
Route::get('topics', [TopicController::class, 'index']);
Route::get('topics/popular', [TopicController::class, 'popularTopics']);
Route::get('topics/{topic}', [TopicController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('topics', [TopicController::class, 'store']);
    Route::put('topics/{topic}', [TopicController::class, 'update']);
    Route::delete('topics/{topic}', [TopicController::class, 'destroy']);
});

// Routes pour les likes des sujets (topics)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('topics/{topic}/like', [LikeController::class, 'storeTopicLike']);
    Route::delete('topics/{topic}/unlike', [LikeController::class, 'destroyTopicLike']);
    Route::get('topics/{topic}/likes', [LikeController::class, 'topicLikes']);
});

// Routes pour les commentaires des sujets (topics)
Route::get('topics/{topic}/comments', [CommentController::class, 'index']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('topics/{topic}/comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, 'update']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
});

// Routes pour les signalements de commentaires
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('comments/{comment}/report', [ReportController::class, 'store']);
});

// Routes pour les fonctionnalités administrateur
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('admin/topics', [AdminController::class, 'topics']);
    Route::post('admin/topics/{topic}/approve', [AdminController::class, 'approveTopic']);
    Route::post('admin/topics/{topic}/reject', [AdminController::class, 'rejectTopic']);
    Route::get('admin/reported-comments', [AdminController::class, 'reportedComments']);
    Route::post('admin/reported-comments/{comment}/resolve', [AdminController::class, 'resolveReportedComment']);
});
