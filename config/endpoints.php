<?php

return [
    "routes" => [
        'users' => [
            'model' => \App\Models\User::class,
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
    ],

    "prefix" => "general",

    "middleware" => [],
];
