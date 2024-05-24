<?php


use Illuminate\Support\Facades\Route;
use Transave\ScolaCbt\Http\Controllers\AnalyticController;
use Transave\ScolaCbt\Http\Controllers\AnswerController;
use Transave\ScolaCbt\Http\Controllers\AuthController;
use Transave\ScolaCbt\Http\Controllers\ExamController;
use Transave\ScolaCbt\Http\Controllers\QuestionController;
use Transave\ScolaCbt\Http\Controllers\QuestionOptionController;
use Transave\ScolaCbt\Http\Controllers\ResourceController;
use Transave\ScolaCbt\Http\Controllers\ResultController;
use Transave\ScolaCbt\Http\Controllers\StudentController;
use Transave\ScolaCbt\Http\Controllers\StudentExamController;
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
    Route::get('{endpoint}', [ ResourceController::class, 'index']);
    Route::get('{endpoint}/{id}', [ResourceController::class, 'show']);
    Route::post('{endpoint}', [ResourceController::class, 'store']);
    Route::match(['post', 'put', 'patch'],'{endpoint}/{id}', [ResourceController::class, 'update']);
    Route::delete('{endpoint}/{id}', [ResourceController::class, 'destroy']);
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
        Route::post('/exam-link/{id}', [ExamController::class, 'generateLink'])->name('link');
        Route::get('/students/{id}', [ExamController::class, 'showWithStudents'])->name('student-list');
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

    //Question Options Routes
    Route::prefix('question-options')->as('options.')->group(function() {
        Route::get('/', [ QuestionOptionController::class, 'index'])->name('index');
        Route::post('/', [QuestionOptionController::class, 'create'])->name('store');
        Route::get('/{id}', [QuestionOptionController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'],'/{id}', [QuestionOptionController::class, 'update'])->name('update');
        Route::delete('/{id}', [QuestionOptionController::class, 'destroy'])->name('delete');
    });

    //Question Routes
    Route::prefix('questions')->as('questions.')->group(function(){
        Route::get('/', [QuestionController::class, 'index'])->name('index');
        Route::post('/', [QuestionController::class, 'create'])->name('store');
        Route::get('/{id}', [QuestionController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'],'/{id}', [QuestionController::class, 'update'])->name('update');
        Route::delete('/{id}', [QuestionController::class, 'destroy'])->name('delete');
    });

    //Question Routes
    Route::prefix('student-exams')->as('student-exams.')->group(function(){
        Route::get('/', [StudentExamController::class, 'index'])->name('index');
        Route::post('/', [StudentExamController::class, 'create'])->name('store');
        Route::get('/{id}', [StudentExamController::class, 'show'])->name('show');
        Route::match(['POST', 'PUT', 'PATCH'],'/{id}', [StudentExamController::class, 'update'])->name('update');
        Route::delete('/{id}', [StudentExamController::class, 'destroy'])->name('delete');
    });

    //User Routes
    Route::prefix('users')->as('users.')->group(function() {
        Route::get('/', [UserController::class, 'users'])->name('index');
        Route::get('/{id}', [UserController::class, 'user'])->name('show');
        Route::post('/{id}', [UserController::class, 'update'])->name('update');
        Route::patch('change-email', [UserController::class, 'changeEmail'])->name('change-email');
        Route::patch('change-password', [UserController::class, 'changePassword'])->name('change-password');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
    });

    Route::prefix('students')->as('students.')->group(function() {
        Route::get('exports', [ StudentController::class, 'export'])->name('export');
        Route::post('upload', [StudentController::class, 'upload'])->name('upload');
        Route::group(['prefix' => 'exams', 'as' => 'exams.'], function () {
            Route::get('/', [ResultController::class, 'fetchStudentExamScores'])->name('index');
            Route::post('/', [ResultController::class, 'fetchStudentExamScore'])->name('show');
        });
    });


    //Results Routes
    Route::prefix('results')->as('results.')->group(function (){
       Route::post('/exam', [ ResultController::class, 'calculateSingleExam'])->name('single-exam');
       Route::post('/exams', [ ResultController::class, 'calculateBatchExams'])->name('batch-exams');
    });

    Route::prefix('reports')->as('reports.')->group(function (){
        Route::get('/', [ AnalyticController::class, 'report'])->name('index');
    });

    Route::get('exam-timetables', [ExamController::class, 'timetable'])->name('exam.timetable');

});
