<?php

namespace system;

// 框架版本号
define('VERSION', '0.1.0');
// 开发模式
define('DEBUG', true);
// 文件目录分割符号，统一为正斜线，仅在目录中区分用。
define('APP_SEGEMENT_SYMBOL', '/');
// 应用根目录
define('APP_PATH', dirname(dirname(__FILE__)).APP_SEGEMENT_SYMBOL);
// 文件后缀
define('APP_SUFFIX', '.php');
// 日志生成目录
define('LOG_PATH', APP_PATH . 'storage' . APP_SEGEMENT_SYMBOL);
// 语言包目录
define('LANG_PATH', APP_PATH . 'Lang' . APP_SEGEMENT_SYMBOL);
// 模版文件目录
define('TPL_PATH', APP_PATH . 'TPL' . APP_SEGEMENT_SYMBOL);
// 本机开发域名，一致代表是开发者模式
define('LOCAL_URL', 'ics.com');
// 此常量是为了兼容老项目商城，而特意加注，在走一些特殊路由的时候，必须加上，等AIPHP可以完全替代这个商城之后，请务必把此常量置为空！！！
define('SPECIAL_ROUTE', APP_SEGEMENT_SYMBOL . 'request' . APP_SUFFIX);

require_once (APP_PATH .  'system'.APP_SEGEMENT_SYMBOL.'Application'.APP_SUFFIX);

Application::init();