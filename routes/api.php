<?php


use Illuminate\Support\Facades\Route;
use Transave\ScolaCbt\Http\Controllers\AuthController;
use Transave\ScolaCbt\Http\Controllers\ExamController;
use Transave\ScolaCbt\Http\Controllers\RestfulAPIController;
use Transave\ScolaCbt\Http\Controllers\SearchController;

$prefix = !empty(config('endpoints.prefix'))? config('endpoints.prefix') : 'general';

/**
 |
 | General routes for RestFul Controller
 | Examples: GET:/cbt/general/sessions, GET:/cbt/general/sessions/1, POST:/cbt/general/sessions,
 | PATCH:/cbt/general/sessions/3, DELETE:/cbt/general/sessions/2
 |
 */
Route::prefix($prefix)->as('cbt.')->group(function() {
//    Route::get('{endpoint}', [RestfulAPIController::class, 'index']);
    Route::get('{endpoint}/{id}', [RestfulAPIController::class, 'show']);
    Route::post('{endpoint}', [RestfulAPIController::class, 'store']);
    Route::match(['post', 'put', 'patch'],'{endpoint}/{id}', [RestfulAPIController::class, 'update']);
    Route::delete('{endpoint}/{id}', [RestfulAPIController::class, 'destroy']);
});

Route::prefix('general')->as('cbt.')->group(function () {
    Route::get('sessions', [SearchController::class, 'indexSessions'])->name('sessions');
    Route::get('faculties', [SearchController::class, 'indexFaculties'])->name('faculties');
    Route::get('departments', [SearchController::class, 'indexDepartments'])->name('departments');
    Route::get('question-options', [SearchController::class, 'indexQuestionOptions'])->name('options');
    Route::get('courses', [SearchController::class, 'indexCourses'])->name('courses');
});

Route::as('cbt.')->group(function () {
    Route::post('login', [ AuthController::class, 'login'])->name('login');
    Route::post('register', [ AuthController::class, 'register'])->name('register');
    Route::get('user', [ AuthController::class, 'user'])->name('user');
    Route::any('logout', [ AuthController::class, 'logout'])->name('logout');

    //Exam Routes
    Route::prefix('exams')->as('exam.')->group(function() {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::post('/', [ExamController::class, 'create'])->name('store');
        Route::post('/{id}', [ExamController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'],'/{id}', [ExamController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExamController::class, 'destroy'])->name('delete');
    });

});
