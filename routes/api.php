<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class,'logout']);
    Route::apiResource('categories', CategoryController::class);

});

Route::middleware(['auth:sanctum','role:admin'])->group(function(){
    Route::apiResource('users', UserController::class);
});
