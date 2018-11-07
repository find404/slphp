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
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => 'ichub',
                'username' => 'root',
                'password' => 'huberp',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'is_master' => true,
            ],
        ],
        'pgsql' => [
            [],
        ],
        'sqlsrv' => [
            [],
        ],
    ],
    'redis' => [
        'cluster' => false,
        'default' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
    ],
];
