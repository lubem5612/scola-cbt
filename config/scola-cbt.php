<?php

return [

    'auth_model' => \Transave\ScolaCbt\Http\Models\User::class,

    'app_env' => env('APP_ENV', 'development'),

    'levels' => [
        1 => '100',
        2 => '200',
        3 => '300',
        4 => '400',
        5 => '500',
        6 => '600'
    ],

    'exam_mode' => [
        1 => 'mock',
        2 => 'graded'
    ],

    'semesters' => [
        1 => 'First',
        2 => 'Second',
    ],

    'question_type' => [
        1 => 'multiple choice',
        2 => 'true or false',
        3 => 'short answers questions',
        4 => 'essay questions',
        5 => 'matching questions',
        6 => 'fill in questions',
        7 => 'diagram or chart questions',
        8 => 'audio questions',
        9 => 'video questions',
    ],

    'user_type' => [
        1 => 'student',
        2 => 'staff',
        3 => 'examiner',
        4 => 'manager',
        5 => 'admin'
    ],

    'route' => [
        'prefix' => 'cbt',
        'middleware' => 'api',
    ],

    'azure' => [
        'storage_url' => 'https://'.env('AZURE_STORAGE_NAME').'.blob.core.windows.net/'.env('AZURE_STORAGE_CONTAINER').'/',
    ],

    'max_score_obtainable' => env('CBT_MAX_EXAM_SCORE', 100),
    
    'difficulty_level' => [
        'very_difficult' => 'very difficult',
        'difficult' => 'difficult',
        'moderate' => 'moderate',
        'simple' => 'simple',
        'very_simple' => 'very simple'
    ],

];