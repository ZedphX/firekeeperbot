<?php

return [
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
    ]
];
