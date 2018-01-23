<?php

return [

    'core' => [
        'name' => 'core',
        'path' => base_path()
    ],

    'app' => [
        'all' => [
            'middleware' => ['web'],
        ],
        'home' => [
            'middleware' => [],
        ]
    ]
];
