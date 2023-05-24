<?php


return [
    "routes" => [
        'users' => [
            'model' => \Transave\ScolaCbt\Models\User::class,
            'rules' => [
                'store' => [
                    'email' => 'required|email',
                    'password' => 'required|min:6',
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

        'students' => [
            'model' => \Transave\ScolaCbt\Models\Student::class,
            'rules' => [
                'store' => [
                    'user_id' => 'required|exists_users,id',
                    'admission_number' => 'required|string',
                ],
                'update' => [
                    'user_id' => 'required|exists_users,id',
                    'admission_number' => 'required|string',
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
