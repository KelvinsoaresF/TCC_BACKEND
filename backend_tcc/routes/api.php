<?php

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
Route::post('/login', [AuthController::class, 'login']);


Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// rotas de usuario
// Route::apiResource('/user', ProfileController::class)->middleware('auth:sanctum');
Route::get('/user', [ProfileController::class, 'show'])->middleware('auth:sanctum');
