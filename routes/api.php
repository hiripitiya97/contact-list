<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) { return $request->user(); });

    Route::prefix('contacts')->group(function () {
        Route::get('/', [ContactController::class, 'index']);
        Route::get('/show/{id}', [ContactController::class, 'show']);
        Route::post('/store', [ContactController::class, 'store']);
        Route::put('/update/{id}', [ContactController::class, 'update']);
        Route::delete('/destroy/{id}', [ContactController::class, 'destroy']);
    });

});
