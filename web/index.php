<?php
/****************************************************************
 * 应用入口文件
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 *****************************************************************/

define('DS', DIRECTORY_SEPARATOR);

define('BASE_PATH', dirname(dirname(__FILE__)));

//加载系统常量
require BASE_PATH . DS . 'config' . DS . 'const.php';

//加载核心文件
require BASE_PATH . DS . 'system' . DS . 'Application.php';

//启动应用
app\system\Application::run();



