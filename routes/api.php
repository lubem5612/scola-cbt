<?php


use Illuminate\Support\Facades\Route;
use Transave\ScolaCbt\Http\Controllers\AuthController;
use Transave\ScolaCbt\Http\Controllers\RestfulAPIController;

$prefix = !empty(config('endpoints.prefix'))? config('endpoints.prefix') : 'general';

/**
 |
 | General routes for RestFul Controller
 | Examples: GET:/cbt/general/sessions, GET:/cbt/general/sessions/1, POST:/cbt/general/sessions,
 | PATCH:/cbt/general/sessions/3, DELETE:/cbt/general/sessions/2
 |
 */
Route::prefix($prefix)->as('cbt.')->group(function() {
    Route::get('{endpoint}', [RestfulAPIController::class, 'index']);
    Route::get('{endpoint}/{id}', [RestfulAPIController::class, 'show']);
    Route::post('{endpoint}', [RestfulAPIController::class, 'store']);
    Route::match(['post', 'put', 'patch'],'{endpoint}/{id}', [RestfulAPIController::class, 'update']);
    Route::delete('{endpoint}/{id}', [RestfulAPIController::class, 'destroy']);
});

Route::as('cbt.')->group(function () {
    Route::post('login', [ AuthController::class, 'login'])->name('login');
    Route::post('register', [ AuthController::class, 'register'])->name('register');
});
