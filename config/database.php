<?php

return [

    // PDO Fetch Style
    'fetch' => PDO::FETCH_CLASS,

    // Default Database Connection Name
    'default' => env('DB_CONNECTION', 'pgsql'),

    // Database Connections
    'connections' => [

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'yoda'),
            'username' => env('DB_USERNAME', 'admin'),
            'password' => env('DB_PASSWORD', 'postgres'),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],

        'pgsql_testing' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'yoda_testing'),
            'username' => env('DB_USERNAME', 'admin'),
            'password' => env('DB_PASSWORD', 'postgres'),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],

        'nlp' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_NLP_DATABASE', 'yoda_nlp'),
            'username' => env('DB_USERNAME', 'admin'),
            'password' => env('DB_PASSWORD', 'postgres'),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],

        'items' => [
            'driver'   => 'sqlite',
            'database' => database_path().'/sqlites/yoda_items.sqlite',
            'charset'  => 'utf8',
        ],
    ],

    'migrations' => 'migrations',
    
    'redis' => [
        'cluster' => false,
        'default' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ],
    ],
    
];
