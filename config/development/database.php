<?php
/**
 * 框架数据库配置相关
 * @var
 */

return [
    'data' => [
        'sqlite' => [
            [],
        ],
        'mysql' => [
            [
                'driver' => 'mysql',
                'host' => '192.168.14.206',
                'port' => '3306',
                'database' => 'XXX',
                'username' => 'root',
                'password' => '123456',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'is_master' => true,
            ],
        ],
        'pgsql' => [
            [
                'driver' => 'pgsql',
                'host' => '192.168.13.237',
                'port' => '5432',
                'database' => 'xxx',
                'username' => 'dev',
                'password' => '123456',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'is_master' => true,
            ],
        ],
        'sqlsrv' => [
            [],
        ],
    ],
    'redis' => [
         [
            'host' => '192.168.14.207',
            'port' => 7000,
            'database' => 0,
            'auth' => 0,
        ],
        [
            'host' => '192.168.14.207',
            'port' => 7001,
            'database' => 0,
            'auth' => 0,
        ],
        [
            'host' => '192.168.14.207',
            'port' => 7002,
            'database' => 0,
            'auth' => 0,
        ],
        [
            'host' => '192.168.14.207',
            'port' => 7003,
            'database' => 0,
            'auth' => 0,
        ],
        [
            'host' => '192.168.14.207',
            'port' => 7004,
            'database' => 0,
            'auth' => 0,
        ],
        [
            'host' => '192.168.14.207',
            'port' => 7005,
            'database' => 0,
            'auth' => 0,
        ],
    ],
];
