<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserProfileController;

Route::prefix('api')->group(function () {
    // Маршрути для коментарів
    Route::get('/comments', [CommentController::class, 'index']);
    Route::post('/comments', [CommentController::class, 'store']);

    // Маршрути для профіля користувача
    Route::get('/user-profile', [UserProfileController::class, 'index']);
    Route::post('/user-profile', [UserProfileController::class, 'store']);
    Route::delete('/user-profile', [UserProfileController::class, 'destroy']);
});

Route::get('/', function () {
    return view('comments');
});

Route::get('/comments', function () {
    return view('comments');
});



