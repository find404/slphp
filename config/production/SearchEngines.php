<?php
/**
 * 搜索引擎配置相关
 * @var
 */

return [
    'coreseek' => [
		[
			'host' => '192.168.14.199',
			'port' => 9312,
            'defaultIndex' => 'pgsqlSearchName',
			'is_master' => true,
		],
		// [
			// 'host' => '192.168.14.199',
			// 'port' => 9312,
			// 'index' => 'pgsqlSearchName',
			// 'is_master' => false,
		// ],
    ],
    'elsticserach' => [],
    'xunserach' => [],
];
