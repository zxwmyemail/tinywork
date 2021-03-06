<?php
/********************************************************************************************
 * redis配置文件
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 ********************************************************************************************/

return [
    'master' => [
        'host' => '127.0.0.1',
        'port' => '6379',
        'auth' => '',
        'pconnect' => false
    ],
    'hashRedis' => [
        ['host' => '10.0.0.1', 'port' => 6379],
        ['host' => '10.0.0.2', 'port' => 6379],
        ['host' => '10.0.0.3', 'port' => 6379],
        ['host' => '10.0.0.4', 'port' => 6379],
        ['host' => '10.0.0.5', 'port' => 6379]
    ],
];

