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
                    'name' => 'required|string|max:50|unique,name',
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
    ],

    "prefix" => "general",

    "middleware" => [],
];
