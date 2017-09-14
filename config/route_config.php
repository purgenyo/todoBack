<?php


return [
    'todo'=>[
        'PUT'=>[
            'todo_id:\d+'=>'update'
        ],
        'POST'=>[
            ''=>'create'
        ],
        'DELETE'=>[
            'todo_id:\d+'=>'delete'
        ],
        'GET'=>[
            ''=>'read',
            'todo_id:\d+'=>'readOne'
        ]
    ],
    'user'=>[
        'POST'=>[
            'registration'=>'registration',
            'login'=>'login'
        ]
    ]
];
