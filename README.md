# AIPHP

写在前面

自研的一份轻快，方便扩展的PHP框架


# 框架划分目录，框架基础层级简介 

```
── appplication
── config
    ├── evelopment
        ├── database.php
        ├── init.php
        ├── hook.php
        ├── mail.php
        ├── services.php
        ├── session.php
        ├── view.php
    ├── production
        ├── database.php
        ├── init.php
        ├── hook.php
        ├── mail.php
        ├── services.php
        ├── session.php
        ├── view.php
── public
    ├── ndex.php
    ├── robots.txt
    ├── favicon.ico
    ├── .htaccess
── storage
── system
    Database
        ├── mysql
            ├── MDB.php
    ├── language
        ├── en
        ├── zh
    ├── Log
        ├── localLog.php
    Redis
        ├── RDB.php
    Application.php
    Autoload.php
    Load.php
    Route.php
```
# application
主要业务实现层 
```
你可以在当前业务层下根据你的实际业务立无数个项目，比如 ：
── 网站前台/
    ├── 控制器（C）
    ├── 视图层（V）
    └── 数据库模型（M）
	└── 核心业务层（S）
	└── 核心逻辑层（L）
── 网站后台/
    ├── 控制器（C）
    ├── 视图层（V）
    └── 数据库模型（M）
	└── 核心业务层（S）
	└── 核心逻辑层（L）
── 手机端/
    ├── 控制器（C）
    ├── 视图层（V）
    └── 数据库模型（M）
	└── 核心业务层（S）
	└── 核心逻辑层（L）
── 小程序端/
    ├── 控制器（C）
    ├── 视图层（V）
    └── 数据库模型（M）
	└── 核心业务层（S）
	└── 核心逻辑层（L）
── APP端/
    ├── 控制器（C）
    ├── 视图层（V）
    └── 数据库模型（M）
	└── 核心业务层（S）
	└── 核心逻辑层（L）
── 公共端/
    ├── 控制器（C）
    ├── 视图层（V）
    └── 数据库模型（M）
	└── 核心业务层（S）
	└── 核心逻辑层（L）
```
# config
业务所需要的配置文件，分为开发者配置，生产环境配置（可含系统配置，也可含功能配置）
## development
开发配置
### database.php
```
<?php

return [
	'data' => [
		'sqlite' => [
			[],
		],
        'mysql' => [
			[
				'driver'    => 'mysql',
				'host'      => '127.0.0.1',
				'port'      => '3306',
				'database'  => 'xxx',
				'username'  => 'root',
				'password'  => '123456',
				'charset'   => 'utf8'
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
				//主从库
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
			'host'     => '127.0.0.1',
			'port'     => 6379,
			'database' => 0,
		],
	],
];

```
# init.php
包含文件初始化需要配置的信息（按业务需求定）

# hook.php
包含自定义钩子方法，多条


# public
网站目录指向文件夹

# storage
日志以及静态化文件存储目录

# system
框架系统目录


# 框架功能开发简介示范例 


HELLO WORLD
``` php
框架的第一步，先来一个HELLO WORLD
在Config目录下面，init.php设置

<?php

return [

    //默认控制器
	'index' => [
		'Project' => 'Home',
		'Controller' => 'IndexClass',
		'Method'=>'index'
	],

    //默认视图模板
    'template' => 'default',
    //默认语言类型
    'language' => 'zh',

];


然后在application下面创建Home文件夹，在创建indexClass控制器，然后创建index方法。

<?php
public function  index ()
{
    echo 'HELLO WORLD';
}
```

数据库连接 
```
有预留主从库连接，对于使用框架使用者来说，是静默操作，开发者无需改变编程方式。

get 获取多条
first 获取单条
update 更新
insert 插入
delete 删除

调用：
$sql = 'select * from wdt_account_log';
$data = $this->get($sql);
```

路由
```
目前支持
项目目录/控制器/方法
action?控制器&m=项目目录&do=方法
无m即为默认项目

预留有
商品ID的URL模式
用户ID的URL模式
用户名的URL模式
```


错误日志记录
```
调用：
\system\LocalLog::write('测试写入记录错误信息','错误级别 ERROR');

说明：
会在storage按项目，控制器方法，生成目录，保存错误记录文件。

```

多语言
```
调用：
echo \system\Load::getLang()['text'];

说明：
在每次控制器加载的时候，会默认同时加载该控制器下语言包，直接调用就行了（但需要事先，按中英文规则写好语言包，在system\langage\下面，按项目\控制器方式创建）

```

多模板
```
调用：
只需要动态设置好值即可（按需求无感实现）

说明：
在项目文件夹的View里面放置多模板，按后台设置即可。

```

加载类
```
动态封装好类调用类，直接以Load方法即可加载
```

HOOK机制
``` 
框架在载入当前控制器方法时候，会同时载入HOOK，开发者只需要在CONFIG/hook.php设置好即可（走HOOK机制的方法，需要为PUBLIC属性）
系统Hook配置，（目前只适用于，当前项目，当前控制下发生的方法，不支持传值）
eg:
//项目
'Home'=>[
     'Controuller'=>[
       //控制器
        'indexClass'=>[
           //钩子发起方法
           'index'=>[
               'setTest'
            ],
        ]
   ]
 ],

```

redis机制
``` 
框架在载入当前控制器方法时候，已经封装好对应的redis_cluster，（后续会追加主从）
开发者只须set和get，无需关系架构部分。

```

```

搜索引擎机制
``` 
框架当前只封装好了coreseek3.2.14，后续会开发elasticsearch.
项目调用
eg:
参数getResultByVagueSearch，是获取coreseek的方法。
参数keywords，是搜索的关键字。
参数index，是获取coreseek的索引。
\system\Load::coreseek('getResultByVagueSearch', $keywords,$index);

```


**框架特有变量简介示范例 **

``` 
//当前URL指向的项目目录
$_SERVER['urlProject']
//当前URL指向的控制器
$_SERVER['urlController']
//当前URL指向的方法
$_SERVER['urlMethod']
//开发者模式和生产环境模式
$_SERVER['ENVIRONMENT']
//当前框架语言类型
$_SERVER['LANGUAGE']
//当前模板目录
$_SERVER['TEMPLATE']


在渲染模板时候，赋予开发者三个特殊且常用变量
//页面数据
self::$_viewData
//当前框架配置值
self::$_config
//当前方法下，模板语言包
self::$_lang
```

# 架构篇

按面向对象为主，面向过程为辅，面向对象的层级设计，为目录结构，和可伸缩的模块加载类的提供类完备的设计思路。

分布式架构思想的引用，在该框架下，随着业务的增长，可以完全适配的新业务功能，按照基本的PHP规范来，就不会随着业务的复杂，不会相互冗余和耦合
沦落为一个意大利面式的设计，框架架构中的，是一个细致而优雅的设计，针对不同的业务有降级耦合度，不同之间的代码业务可以复用和整合，按需时调用，不浪费任何服务器额外内存。



# 数据篇


在框架首页，简单的sql查询插入，普通业务代码，简易算法。
在ab 压测请求数10000 并发4 的情况下

```
Server Software:        Apache/2.4.23 (Win32) OpenSSL/1.0.2j mod_fcgid/2.3.9
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /
Document Length:        36 bytes

Concurrency Level:      4
Time taken for tests:   11.643 seconds
Complete requests:      10000
Failed requests:        0
Total transferred:      2600000 bytes
HTML transferred:       360000 bytes
Requests per second:    858.89 [#/sec] (mean)
Time per request:       4.657 [ms] (mean)
Time per request:       1.164 [ms] (mean, across all concurrent requests)
Transfer rate:          218.08 [Kbytes/sec] received
```

因为时间关系，没有测试nginx，PHP7有opcache 版本和无opcache 版本，但是以此为基准，只会更快。


# 写在后面

在以PHP5.6.27的基础上开发，但是开发遵循PHP7.2.0的开发原则，AIPHP可以在PHP5.6.27 和 PHP7.2.0共存，为以后升级做基础。

因为时间关系，现在框架只实现类目前功能所需要的框架功能，在后续的开发中会按需一一增加，而框架中也有一套预留类加载新功能类的方法，