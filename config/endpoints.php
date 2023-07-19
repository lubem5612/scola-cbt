<?php


return [
    "routes" => [
        'sessions' => [
            'model' => \Transave\ScolaCbt\Http\Models\Session::class,
            'rules' => [
                'store' => [
                    'name' => 'required|unique:sessions,name',
                    'is_active' => 'sometimes|required|in:no,yes',
                ],
                'update' => [
                    'name' => 'sometimes|string|max:60'
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => [],
        ],

        'faculties' => [
            'model' => \Transave\ScolaCbt\Http\Models\Faculty::class,
            'rules' => [
                'store' => [
                    'name' => 'required|string|max:50|unique:faculties,name',
                ],
                'update' => [
                    'name' => 'sometimes|required|string|max:50',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => [],
        ],

        'courses' => [
            'model' => \Transave\ScolaCbt\Http\Models\Course::class,
            'rules' => [
                'store' => [
                    'name' => 'required|string',
                    'code' => 'required|string',
                    'credit_load' => 'sometimes|required|integer|in:1,2,3,4,5,6',
                ],
                'update' => [
                    'name' => 'sometimes|required|string',
                    'code' => 'sometimes|required|string',
                    'credit_load' => 'sometimes|required|integer|in:1,2,3,4,5,6',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => [],
        ],

        'departments' => [
            'model' => \Transave\ScolaCbt\Http\Models\Department::class,
            'rules' => [
                'store' => [
                    'name' => 'required|unique:departments,name',
                    'faculty_id' => 'required|exists:faculties,id',
                ],
                'update' => [
                    'name' => 'sometimes|string|max:60',
                    'faculty_id' => 'sometimes|required|exists:faculties,id'
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['faculty'],
        ],

        'question-options' => [
            'model' => \Transave\ScolaCbt\Http\Models\Option::class,
            'rules' => [
                'store' => [
                    'question_id' => 'required|exists:questions,id',
                    'is_correct_option' => 'required|string|in:no,yes',
                    'content' => 'required|string'
                ],
                'update' => [
                    'question_id' => 'sometimes|required|exists:questions,id',
                    'is_correct_option' => 'sometimes|required|string|in:no,yes',
                    'content' => 'sometimes|required|string'
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['question'],
        ],
    ],

    "prefix" => "general",

    "middleware" => [],
];
