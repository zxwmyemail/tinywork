<?php
namespace app\system;
/********************************************************************************************
 * 应用驱动类
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 ********************************************************************************************/
use app\system\core\Route;
use app\system\library\Log;

final class Application {

    public static  $_config = null;         //系统配置参数，对应params.config.php文件
    public static  $_reqParams = null;      //请求url参数

    /*---------------------------------------------------------------------------------------
    | 创建应用
    |----------------------------------------------------------------------------------------
    | @access      public
    | @param       array   $config
    ----------------------------------------------------------------------------------------*/
    public static function run($config) {
        self::registerAutoload();

        //加载config/下的参数配置params.config.php
        self::$_config = $config['system'];

        //初始化系统错误是否显示
        self::isDisplayErrors();

        //防止sql注入检查
        self::checkRequestParams();

        //获取路由对象
        $route = new Route(self::$_config['route']['url_type']);

        //将url参数转换成数组 
        self::$_reqParams = $route->getUrlArray();   

        //导向控制层
        self::routeToCtrl(self::$_reqParams);
    }

    /*---------------------------------------------------------------------------------------
    | 自动类加载函数
    |----------------------------------------------------------------------------------------
    | @access      public
    | @param       string   $classname  类名
    ----------------------------------------------------------------------------------------*/
    public static function classLoader($classname) {     
        $filePath = str_replace(GLOBAL_NAMESPACE, BASE_PATH, $classname) . ".php";
        $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $filePath);
        if (file_exists($filePath)) {
            require_once($filePath); 
        } else {
            error_log('[' . date('Y-m-d H:i:s') . '][ERROR] 加载 ' . $filePath . ' 类库不存在');
            header('Location: index.php?m=home&r=home.page404');
        } 
    }

    /*------------------------------------------------------------------------------------------
    | 注册自动加载类函数
    --------------------------------------------------------------------------------------------*/
    public static function registerAutoload($enable = true) {
        $enable ? spl_autoload_register('self::classLoader') : spl_autoload_unregister('self::classLoader');
    }

    /*-------------------------------------------------------------------------------------
    | 根据目前处于开发、测试还是生产模式，判断是否显示错误到页面
    --------------------------------------------------------------------------------------*/
    public static function isDisplayErrors() {
        error_reporting(E_ALL);
        switch (CUR_ENV) {
            case 'development':
                ini_set('display_errors', 1);
                break;
            case 'test':
                ini_set('display_errors', 1);
                break;
            case 'product':
                ini_set('display_errors', 0);
                break;
            default:
                exit('The application environment is not set correctly.');
                break;
        }
        date_default_timezone_set('Asia/Shanghai');
        ini_set('log_errors', 1); 
        ini_set('error_log', LOG_PATH . DS . 'sys_log' . DS . date('Ymd').'.log');
    }

    /*---------------------------------------------------------------------------------------
    | 根据URL分发到Controller
    |----------------------------------------------------------------------------------------
    | @access      public 
    | @param       array   $url_array     
    ---------------------------------------------------------------------------------------*/
    public static function routeToCtrl($url_array) {   
        $routeInfo = self::getMCA();

        $module     = $routeInfo['module'].'Module';
        $controller = $routeInfo['controller'].'Controller';
        $action     = $routeInfo['action'].'Action';
        
        $controllerClass = GLOBAL_NAMESPACE . "\mvc\controller\\".$module."\\".$controller;
        $controller = new $controllerClass;

        if($action && method_exists($controller, $action)){
            $params = empty($url_array['params']) ? '' : $url_array['params'];
            isset($params) ? $controller->$action($params) : $controller->$action();
        } else {
            $errMsg = '无效路由:m=' . $routeInfo['module'] . '&r=' . $routeInfo['controller'] . '.' . $routeInfo['action'];
            error_log('[' . date('Y-m-d H:i:s') . '][ERROR] ' . $errMsg);
            header('Location: index.php?m=home&r=home.page404');
        }
        exit(0);
    }

    /*---------------------------------------------------------------------------------------
    | 获取module、controller和action   
    ---------------------------------------------------------------------------------------*/
    public static function getMCA() {   
        $_reqParams = self::$_reqParams;
        $routeConf = self::$_config['route'];

        return [
            'module'     => empty($_reqParams['module']) ? $routeConf['default_module'] : $_reqParams['module'],
            'controller' => empty($_reqParams['controller']) ? $routeConf['default_controller']: $_reqParams['controller'],
            'action'     => empty($_reqParams['action']) ? $routeConf['default_action']: $_reqParams['action'],
        ];
    }
    
    /*---------------------------------------------------------------------------------------
    | 为防止sql注入和xss攻击，对提交参数进行检查
    ---------------------------------------------------------------------------------------*/
    public static function checkRequestParams() {
        $magic_quotes_gpc = get_magic_quotes_gpc(); 

        self::daddslashes($_COOKIE); 
        self::daddslashes($_POST); 
        self::daddslashes($_GET); 
        self::daddslashes($_REQUEST); 

        if(!$magic_quotes_gpc) { 
            $_FILES = self::daddslashes($_FILES); 
        }
    }

    /*---------------------------------------------------------------------------------------
    | 防止sql注入和xss攻击
    ---------------------------------------------------------------------------------------*/
    public static function daddslashes($data, $ignore_magic_quotes = true) {
        if(is_string($data)) {   //防止被挂马，跨站攻击
            $data = self::cleanXss($data, true);      
            if(($ignore_magic_quotes == true) || (!get_magic_quotes_gpc())) {  //防止sql注入
                $data = addslashes($data);            
            }
        } else if(is_array($data)) {
            foreach($data as $key => $value) {
                $data[$key] = self::daddslashes($value, $ignore_magic_quotes);
            }
        }
        return $data;
    }

    /*---------------------------------------------------------------------------------------
    | 防止xss攻击
    |----------------------------------------------------------------------------------------
    | @param $string
    | @param $low 安全别级低
    ----------------------------------------------------------------------------------------*/
    public static function cleanXss(&$string, $low = false) {
        if (is_array ($string)) {
            foreach ($string as $value) {
                self::cleanXss($value);
            }   
        } else {
            $string = trim($string);
            $string = strip_tags($string);
            $string = htmlspecialchars($string);

            if ($low) return $string;

            $string = str_replace(array('"', "'", "..", "../", "./"), '', $string);
            $no = '/%0[0-8bcef]/';
            $string = preg_replace($no, '', $string);
            $no = '/%1[0-9a-f]/';
            $string = preg_replace($no, '', $string);
            $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
            $string = preg_replace($no, '', $string);
            return $string;
        }
    }
}

