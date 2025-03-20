<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Oi estou usando laravel e minha api funcionouUUUUUUUUUUUUUUUUUUUUUU";
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
