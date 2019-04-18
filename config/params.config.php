<?php
/********************************************************************************************
 * 系统配置文件
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 ********************************************************************************************/

/*-------------------------------------------------------------------------------------------
| mysql数据库参数配置
|--------------------------------------------------------------------------------------------
| 参数说明
| db_conn   数据库连接表示，1为长久链接，0为即时链接
-------------------------------------------------------------------------------------------*/
$_CONFIG['system']['mysql'] = [
    'master' => [
        'db_host'          => 'localhost',
        'db_user'          => 'root',
        'db_port'          => '3306',
        'db_password'      => 'ello-admin@163.com',
        'db_database'      => 'mysql',
        'db_table_prefix'  => 'app_',
        'db_charset'       => 'utf8',
        'db_conn'          => 0
    ],
    'slave'  => [
        'db_host'          => 'localhost',
        'db_user'          => 'root',
        'db_port'          => '3306',
        'db_password'      => '',
        'db_database'      => 'mysql',
        'db_table_prefix'  => 'app_',
        'db_charset'       => 'utf8',
        'db_conn'          => 0
    ]
];


/*-------------------------------------------------------------------------------------------
| 默认路由配置和url形式配置
|--------------------------------------------------------------------------------------------
| 使用说明 
| 1.default_controller  系统默认控制器
| 2.default_action      系统默认控制器方法
| 3.url_type            定义URL的形式
|                       1为普通模式   index.php?r=controller.action&id=2
|                       2为PATHINFO   index.php/controller/action/?id=2
--------------------------------------------------------------------------------------------*/
$_CONFIG['system']['route'] = [
    'default_module'     => 'home',
    'default_controller' => 'home', 
    'default_action'     => 'index', 
    'url_type'           => 1                                                                           
];


/*------------------------------------------------------------------------------------------
| redis缓存配置
|-------------------------------------------------------------------------------------------
| 参数说明 
| 1.host             主机IP
| 2.auth             主机授权密码
| 3.port             端口号
------------------------------------------------------------------------------------------*/
$_CONFIG['system']['redis'] = [
    'master' => [
        'host'=>'127.0.0.1',
        'port'=>'6379',
        'auth'=>'',
    ],
    'slave' => [
        'host'=>'127.0.0.1',
        'port'=>'6379',
        'auth'=>'',
    ]
];


/*------------------------------------------------------------------------------------------
| memcache缓存配置
|-------------------------------------------------------------------------------------------
| 参数说明 
| 1.server             memcache服务器地址端口配置，weight为优先级
| 2.expiration         过期时间
| 3.prefix             键值前缀
| 4.compression        使用MEMCACHE_COMPRESSED标记对数据进行压缩(使用zlib)。
| 5.isAutoTresh        是否开启大值自动压缩
| 6.threshold          控制多大值进行自动压缩的阈值
| 7.thresavings        指定经过压缩实际存储的值的压缩率，支持的值必须在0和1之间。
|                      默认值是0.2表示20%压缩率。
-------------------------------------------------------------------------------------------*/
$_CONFIG['system']['memcache'] = [
    'server' => [
        ['host' => '127.0.0.1', 'port' => '11211', 'weight' => 1],
        ['host' => '127.0.0.1', 'port' => '11211', 'weight' => 1]
    ],
    'expiration'    =>  18600, 
    'prefix'        =>  'zxw_', 
    'compression'   =>  false,
    'isAutoTresh'   =>  true,
    'threshold'     =>  20000, 
    'thresavings'   =>  0.2 
];

