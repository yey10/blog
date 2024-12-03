<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store']); // Crear post
    Route::get('/posts', [PostController::class, 'index']); // Obtener todos los posts
    Route::get('/posts/{id}', [PostController::class, 'show']); // Obtener un post específico
    Route::put('/posts/{id}', [PostController::class, 'update']); // Actualizar post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); // Eliminar post
});
