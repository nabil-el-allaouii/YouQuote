<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CitationController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/random/{id}', [CitationController::class, 'random']);
Route::get('/filter', [CitationController::class, 'filter']);
Route::get('/show/{id}', [CitationController::class, 'visitorShow']);
Route::get('/popular', [CitationController::class, 'popular']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/citation/create', [CitationController::class, 'store'])->name('citation.create');
    Route::get('/citation/show', [CitationController::class, 'index']);
    Route::get('/citation/{id}', [CitationController::class, 'show']);
    Route::delete('/citation/delete/{id}', [CitationController::class, 'destroy']);
    Route::put('/citation/edit/{id}', [CitationController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/like/{id}',[CitationController::class ,'like']);
});

Route::middleware('auth:sanctum', 'approveByAdmin')->group(function () {
    Route::patch('/approve/{id}', [CitationController::class, 'approve']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
    Route::apiResource('/tag' , TagController::class);
});
