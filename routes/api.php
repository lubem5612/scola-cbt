<?php


use Illuminate\Support\Facades\Route;
use Transave\ScolaCbt\Http\Controllers\AnswerController;
use Transave\ScolaCbt\Http\Controllers\AuthController;
use Transave\ScolaCbt\Http\Controllers\ExamController;
use Transave\ScolaCbt\Http\Controllers\QuestionController;
use Transave\ScolaCbt\Http\Controllers\RestfulAPIController;
use Transave\ScolaCbt\Http\Controllers\SearchController;
use Transave\ScolaCbt\Http\Controllers\UserController;

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
    //Auth Routes
    Route::post('login', [ AuthController::class, 'login'])->name('login');
    Route::post('register', [ AuthController::class, 'register'])->name('register');
    Route::get('user', [ AuthController::class, 'user'])->name('user');
    Route::post('resend-token', [ AuthController::class, 'resendToken'])->name('resend-token');
    Route::post('verify-email', [ AuthController::class, 'verifyEmail'])->name('verify-email');
    Route::any('logout', [ AuthController::class, 'logout'])->name('logout');

    //Exam Routes
    Route::prefix('exams')->as('exams.')->group(function() {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::post('/', [ExamController::class, 'create'])->name('store');
        Route::get('/{id}', [ExamController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'],'/{id}', [ExamController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExamController::class, 'destroy'])->name('delete');
    });

    //Answer Routes
    Route::prefix('answers')->as('answers.')->group(function() {
        Route::get('/', [AnswerController::class, 'index'])->name('index');
        Route::post('/', [AnswerController::class, 'create'])->name('store');
        Route::get('/{id}', [AnswerController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'],'/{id}', [AnswerController::class, 'update'])->name('update');
        Route::delete('/{id}', [AnswerController::class, 'destroy'])->name('delete');
    });

    //Question Routes
    Route::prefix('questions')->as('questions.')->group(function(){
        Route::get('/', [QuestionController::class, 'index'])->name('index');
        Route::post('/', [QuestionController::class, 'create'])->name('store');
        Route::get('/{id}', [QuestionController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'],'/{id}', [QuestionController::class, 'update'])->name('update');
        Route::delete('/{id}', [QuestionController::class, 'destroy'])->name('delete');
    });

    Route::prefix('users')->as('users.')->group(function() {
        Route::get('/', [UserController::class, 'users'])->name('index');
        Route::post('/{id}', [UserController::class, 'update'])->name('update');
        Route::patch('change-email', [UserController::class, 'changeEmail'])->name('change-email');
        Route::patch('change-password', [UserController::class, 'changePassword'])->name('change-password');
    });

});
