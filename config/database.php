<?php
/********************************************************************************************
 * 数据库配置文件
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 ********************************************************************************************/

return [
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