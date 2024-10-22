<?php

return [
    /*
     |
     | Set the model for authentication
     | you should set this to your application's user model i.e App\Models\User::class
     | if this is not set, the package user model will be used
     | the user model from your application should include the UserHelper trait from the package
     |
     */
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

//    'azure' => [
//        'id' => '',
//        'storage_url' => 'https://'.env('AZURE_STORAGE_NAME').'.blob.core.windows.net/'.env('AZURE_STORAGE_CONTAINER').'/',
//    ],
//
//    's3' => [
//        'storage_url' => 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com',
//        'id' => 'amazonaws.com',
//    ],

    'max_score_obtainable' => env('CBT_MAX_EXAM_SCORE', 100),
    
    'difficulty_level' => [
        'very_difficult' => 'very difficult',
        'difficult' => 'difficult',
        'moderate' => 'moderate',
        'easy' => 'easy',
        'very_easy' => 'very easy'
    ],
    
    'file_storage' => [
        
        'default_disk' => env('FILESYSTEM_DISK', 'local'),
    
        'storage_prefix' => env('STORAGE_PREFIX', 'bookstore'),
    
        'disks' => [
            'azure' => [
                'storage_url' => 'https://'.env('AZURE_STORAGE_NAME').'.blob.core.windows.net/'.env('AZURE_STORAGE_CONTAINER'),
                'id' => '.windows.net',
            ],
        
            's3' => [
                'storage_url' => 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com',
                'id' => 'amazonaws.com',
            ],
        
            'local' => [
                'storage_url' => '',
                'id' => '',
            ],
        ],
    ],

];