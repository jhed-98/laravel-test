<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

Route::apiResource('tasks', TaskController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Validar los accesos  de los api con Tokens
Route::get('/prueba',function(){
    $user = JWTAuth::parseToken()->authenticate();
    return $user;
});
