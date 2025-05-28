<?php
use App\Http\Controllers\AnimalPostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InteractionPostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Oi estou usando laravel e minha api funcionou, usando a rota API api.php";
});

// rotas de autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/show-post/{id}', [AnimalPostController::class, 'show']);
Route::get('/posts', [AnimalPostController::class, 'index']);
Route::get('/public-profile/{id}', [ProfileController::class, 'publicProfile']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', [ProfileController::class, 'show'])->middleware('auth:sanctum');

    Route::post('/add-post', [AnimalPostController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/my-posts', [ProfileController::class, 'myPosts'])->middleware('auth:sanctum');
    Route::put('/edit-profile', [ProfileController::class, 'editProfile'])->middleware('auth:sanctum');

    Route::put('/edit-post/{id}', [AnimalPostController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/delete-post/{id}', [AnimalPostController::class, 'destroy'])->middleware('auth:sanctum');

    // salvar post
    Route::post('/save-post/{id}', [InteractionPostController::class, 'savePost'])->middleware('auth:sanctum');
    Route::get('/saved-posts', [InteractionPostController::class, 'indexSaves'])->middleware('auth:sanctum');
    Route::delete('/remove-save/{id}', [InteractionPostController::class, 'removeSave'])->middleware('auth:sanctum');

    Route::post('/like-post/{id}', [InteractionPostController::class, 'postLike'])->middleware('auth:sanctum');
});

