<?php


return [
    'todo'=>[
        'PUT'=>[
            'todo_id:[0-9]/fuck/the'=>'update'
        ],
        'POST'=>[
            ''=>'create'
        ],
        'DELETE'=>[
            'todo_id:[0-9]'=>'delete'
        ],
        'GET'=>[
            ''=>'read',
            'todo_id:[0-9]'=>'readOne'
        ]
    ],
    'todo2'=>[
        'PUT'=>[
            'todo_id:[0-9]/fuck/the'=>'update'
        ],
        'POST'=>[
            ''=>'create'
        ],
        'DELETE'=>[
            'todo_id:[0-9]'=>'delete'
        ],
        'GET'=>[
            ''=>'read',
            'todo_id:[0-9]'=>'readOne'
        ]
    ]
];
