<?php

return [
    'statuses' => [
        'success' => 'Success',
        'error' => 'Error',
    ],
    'aliases' => [
        'hollow',
        'unkindled',
        'ashen_one'
    ],
    'default_alias' => 'unkindled',
    'supported_languages' => [
        'en' => 'English',
        'es' => 'Español'
    ],
    'inline_commands' => [
        'remind' => ['remind me', 'remindme', 'recuerdame']
    ],
    'callback_query_types' => [
        'language' => 'language:',
        'alias' => 'alias:'
    ],
    'reminder_delimiters' => [
        'date' => [ //Real date. Example: on March 22nd
            'on',
            'el'
        ],
        'time' => [ //Time expression. Example: in 20 minutes
            'in',
            'en'
        ]
    ]
];
