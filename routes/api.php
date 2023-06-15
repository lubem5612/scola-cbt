<?php


use Illuminate\Support\Facades\Route;
use Transave\ScolaCbt\Http\Controllers\AuthController;
use Transave\ScolaCbt\Http\Controllers\RestfulAPIController;

$prefix = !empty(config('endpoints.prefix'))? config('endpoints.prefix') : 'general';


Route::group(['prefix' => 'cbt', 'middleware' => ['api']], function() use($prefix){
    Route::group(['prefix' => $prefix], function () {
        Route::get('{endpoint}', [RestfulAPIController::class, 'index']);
        Route::get('{endpoint}/{id}', [RestfulAPIController::class, 'show']);
    });

    //other public routes here
    Route::as('cbt.')->group(function() {
        Route::post('login', [ AuthController::class, 'login'])->name('login');
        Route::post('register', [ AuthController::class, 'register'])->name('register');
    });

});

Route::group(['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']], function() use($prefix){
    Route::group(['prefix' => $prefix], function () {
        Route::post('{endpoint}', [RestfulAPIController::class, 'store']);
        Route::match(['post', 'put', 'patch'],'{endpoint}/{id}', [RestfulAPIController::class, 'update']);
        Route::delete('{endpoint}/{id}', [RestfulAPIController::class, 'destroy']);
    });

    //other secured routes here

});