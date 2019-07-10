<?php
/********************************************************************************************
 * 系统配置文件
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 ********************************************************************************************/

/*--------------------------------------------------------------------------------------------
| 全局命名空间
---------------------------------------------------------------------------------------------*/
define('GLOBAL_NAMESPACE','app');

/*---------------------------------------------------------------------------------------------
| 设置环境模式
|----------------------------------------------------------------------------------------------
| 1.开发模式(development) 
| 2.测试模式(test)
| 3.生产环境模式(product)
---------------------------------------------------------------------------------------------*/
define('CUR_ENV','development');
// define('CUR_ENV','test');
// define('CUR_ENV','product');

/*--------------------------------------------------------------------------------------------
| 设置配置文件路径
|---------------------------------------------------------------------------------------------
| 常量BASE_PATH从入口文件index.php获得 
---------------------------------------------------------------------------------------------*/
define('CONFIG_PATH', BASE_PATH . DS . 'config');

/*--------------------------------------------------------------------------------------------
| 设置系统资源文件路径
---------------------------------------------------------------------------------------------*/
define('RESOURCE_PATH', BASE_PATH . DS . 'web' . DS . 'resources');

/*--------------------------------------------------------------------------------------------
| 设置公共类库文件路径
---------------------------------------------------------------------------------------------*/
define('APP_EXTEND_PATH', BASE_PATH . DS . 'extend');

/*--------------------------------------------------------------------------------------------
| 设置控制层类文件路径
---------------------------------------------------------------------------------------------*/
define('CONTROLLER_PATH', BASE_PATH . DS . 'mvc' . DS . 'controller');

/*--------------------------------------------------------------------------------------------
| 设置model类文件路径
---------------------------------------------------------------------------------------------*/
define('MODEL_PATH', BASE_PATH . DS . 'mvc' . DS . 'model');

/*--------------------------------------------------------------------------------------------
| 设置视图层类文件路径
---------------------------------------------------------------------------------------------*/
define('VIEW_PATH', BASE_PATH . DS . 'mvc' . DS . 'view');

/*--------------------------------------------------------------------------------------------
| 设置日志文件路径
---------------------------------------------------------------------------------------------*/
define('LOG_PATH', BASE_PATH . DS . 'log');

/*--------------------------------------------------------------------------------------------
| 设置系统文件路径
---------------------------------------------------------------------------------------------*/
define('SYSTEM_PATH', BASE_PATH . DS . 'system');

/*--------------------------------------------------------------------------------------------
| 设置系统类库文件路径
---------------------------------------------------------------------------------------------*/
define('SYS_LIB_PATH', BASE_PATH . DS . 'system' . DS . 'library');

/*--------------------------------------------------------------------------------------------
| 设置第三方类库文件路径
---------------------------------------------------------------------------------------------*/
define('SYS_FRAMEWORK_PATH', BASE_PATH . DS . 'system' . DS . 'framework');

/*--------------------------------------------------------------------------------------------
| 设置系统核心类文件路径
---------------------------------------------------------------------------------------------*/
define('SYS_CORE_PATH', BASE_PATH . DS . 'system' . DS . 'core');
