<?php

use App\Http\Controllers\AnimalPostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Oi estou usando laravel e minha api funcionou, usando a rota API api.php";
});

Route::get('/db-test', function() {
    try {
        # code...
        DB::connection()->getPdo();
        return "Ok conexão do banco funfou";
    } catch (\Exception $e) {
        # code...
        return "essa porra não funfou" . $e;
    }
});

Route::get('/test', function() {
    return "Teste de rota postman usando api.php";
});

// rotas de autenticação
Route::post('/register', [AuthController::class, 'register']);

//rota protegida
Route::post('/login', [AuthController::class, 'login']);
Route::get('/show-post/{id}', [AnimalPostController::class, 'show']);
Route::get('/posts', [AnimalPostController::class, 'index']);


Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', [ProfileController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/add-post', [AnimalPostController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/my-posts', [ProfileController::class, 'myPosts'])->middleware('auth:sanctum');
    Route::put('/edit-profile', [ProfileController::class, 'editProfile'])->middleware('auth:sanctum');
});

