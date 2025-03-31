<?php

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


Route::post('/register', [UserController::class, 'register']);
