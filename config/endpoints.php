<?php


return [
    "routes" => [
        'sessions' => [
            'table' => 'sessions',
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
            'table' => 'faculties',
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
            'table' => 'courses',
            'model' => \Transave\ScolaCbt\Http\Models\Course::class,
            'rules' => [
                'store' => [
                    'department_id' => 'required|exists:departments,id',
                    'name' => 'required|string',
                    'code' => 'required|string|max:10',
                    'credit_load' => 'sometimes|required|integer|in:1,2,3,4,5,6',
                ],
                'update' => [
                    'name' => 'sometimes|required|string',
                    'code' => 'sometimes|required|string|max:10',
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
            'table' => 'departments',
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
            'table' => 'options',
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
        'exam-settings' => [
            'table' => 'exam_settings',
            'model' => \Transave\ScolaCbt\Http\Models\ExamSetting::class,
            'rules' => [
                'store' => [
                    'exam_id' => 'required|exists:exams,id',
                    'show_max_scores' => 'sometimes|required|in:0,1',
                    'display_question_randomly' => 'sometimes|required|in:0,1',
                    'allow_multiple_attempts' => 'sometimes|required|in:0,1',
                    'is_public_access' => 'sometimes|required|in:0,1',
                    'browser_warn_level' => 'sometimes|required|in:0,1,2',
                    'farewell_message' => 'required|string',
                    'unordered_answering' => 'sometimes|required|in:0,1',
                    'set_pass_mark' => 'sometimes|required|in:0,1',
                    'pass_mark_value' => 'required_if:set_pass_mark,1|numeric|min:20|max:99',
                    'pass_mark_unit' => 'required_if:set_pass_mark,1|string|in:points,percent',
                    'grade_with_points' => 'sometimes|required|in:0,1',
                    'send_result_mail' => 'sometimes|required|in:0,1',
                    'send_congratulatory_mail' => 'sometimes|required|in:0,1',
                ],
                'update' => [
                    'exam_id' => 'sometimes|required|exists:exams,id',
                    'show_max_scores' => 'sometimes|required|in:0,1',
                    'display_question_randomly' => 'sometimes|required|in:0,1',
                    'allow_multiple_attempts' => 'sometimes|required|in:0,1',
                    'is_public_access' => 'sometimes|required|in:0,1',
                    'browser_warn_level' => 'sometimes|required|in:0,1,2',
                    'farewell_message' => 'sometimes|required|string',
                    'unordered_answering' => 'sometimes|required|in:0,1',
                    'set_pass_mark' => 'sometimes|required|in:0,1',
                    'pass_mark_value' => 'sometimes|required_if:set_pass_mark,1|numeric|min:20|max:99',
                    'pass_mark_unit' => 'sometimes|required_if:set_pass_mark,1|string|in:points,percent',
                    'grade_with_points' => 'sometimes|required|in:0,1',
                    'send_result_mail' => 'sometimes|required|in:0,1',
                    'send_congratulatory_mail' => 'sometimes|required|in:0,1',
                ]
            ],
            'order' => [
                'column' => 'created_at',
                'pattern' => 'DESC',
            ],
            'relationships' => ['exam'],
        ],
    ],

    "prefix" => "general",

    "middleware" => [],
];
