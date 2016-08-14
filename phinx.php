<?php

require_once __DIR__ . '/bootstrap.php';

return [
    'paths' => [
        'migrations' => __DIR__ . '/db/migrations',
        'seeds' => __DIR__ . '/db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'name' => Abhayagiri\Config::get('db', 'database'),
            'connection' => Abhayagiri\DB::getPDOConnection(),
        ]
    ]
];

?>
