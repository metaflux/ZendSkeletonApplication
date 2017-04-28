<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db' => [
        // Used for zenddevelopertools
//        'driver' => 'Pdo',
//        'dsn' => 'mysql:host=192.168.12.9;dbname=zf;charset=utf8',
//        'username' => 'zf',
//        'password' => 'zf',

        'adapters' => [
            'dbRead' => [
                'driver' => 'Pdo',
                'dsn' => 'mysql:host=192.168.12.9;dbname=zf;charset=utf8',
                'username' => 'zf',
                'password' => 'zf',
            ],
            'dbWrite' => [
                'driver' => 'Pdo',
                'dsn' => 'mysql:host=192.168.12.9;dbname=zf;charset=utf8',
                'username' => 'zf',
                'password' => 'zf',
            ],
        ],
    ]
];
