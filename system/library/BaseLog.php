<?php
namespace app\system\library;
/********************************************************************************************
  BaseLog写日志类 
********************************************************************************************/
use app\system\core\Config;

class BaseLog {
    
    private static $_logInstance;
    private static $_logHandle;
    private static $_defaultModule;

    const DEBUG    = 'DEBUG';       //调试bug级别
    const INFO     = 'INFO';        //信息提示级别
    const NOTICE   = 'NOTICE';      //通知级别
    const WARNING  = 'WARNING';     //警告级别
    const ERROR    = 'ERROR';       //错误级别
    const CRITICAL = 'CRITICAL';    //严重级别
    
    private function __construct() {
        $conf = Config::get('config', 'route');
        self::$_defaultModule = $conf['default_module'];

        $logDir = LOG_PATH.'/app_log/' . date('Y') . '/';
        if (!is_dir($logDir)) 
            mkdir($logDir, 0777, true); 

        $logFile = $logDir . date('Y-m-d') . '.log';
        if (!is_writable($logFile)) {
            chmod($logFile, 0777);
        } 
        self::$_logHandle = @fopen($logFile,'a+');
        if(!is_resource(self::$_logHandle)){
            throw new \Exception('无效的文件路径');
        }
    }

    /*------------------------------------------------------------------------------------------
    | 析构函数，删除文件资源句柄
    -------------------------------------------------------------------------------------------*/
    public function __destruct()
    {  
        // 释放句柄资源
        if(is_resource(self::$_logHandle)){
            fclose(self::$_logHandle); 
        } 
    } 

    /*------------------------------------------------------------------------------------------
    | 获取日志单例
    ------------------------------------------------------------------------------------------*/
    public static function getInstance()
    {
        if(!(self::$_logInstance instanceof self)){
            self::$_logInstance = new self();
        }
        return self::$_logInstance;
    }

    /*------------------------------------------------------------------------------------------
    | 写日志调试信息
    -------------------------------------------------------------------------------------------*/
    public static function debug($msg, $module = '')
    {
        if (empty($msg)) return;

        self::getInstance();

        $time = date('Y-m-d H:i:s');
        $msg = str_replace(array("\n","\t"),array("",""),$msg);
        $module = str_replace(array("\n","\t"),array("",""),$module);

        $logLine = "[$time][" . self::DEBUG . "][$module]:$msg\r\n";
        fwrite(self::$_logHandle, $logLine);
    }

    /*------------------------------------------------------------------------------------------
    | 写日志普通信息
    -------------------------------------------------------------------------------------------*/
    public static function info($msg, $module = '')
    {
        if (empty($msg)) return;

        self::getInstance();

        $time = date('Y-m-d H:i:s');
        $msg = str_replace(["\n","\t"], ["",""], $msg);
        $module = empty($module) ? self::$_defaultModule : str_replace(["\n", "\t"], ["", ""], $module);

        $logLine = "[$time][" . self::INFO . "][$module]:$msg\r\n";
        fwrite(self::$_logHandle, $logLine);
    }

    /*------------------------------------------------------------------------------------------
    | 写日志通知信息
    -------------------------------------------------------------------------------------------*/
    public static function notice($msg, $module = '')
    {
        if (empty($msg)) return;

        self::getInstance();

        $time = date('Y-m-d H:i:s');
        $msg = str_replace(array("\n","\t"),array("",""),$msg);
        $module = empty($module) ? self::$_defaultModule : str_replace(["\n", "\t"], ["", ""], $module);

        $logLine = "[$time][" . self::NOTICE . "][$module]:$msg\r\n";
        fwrite(self::$_logHandle, $logLine);
    }

    /*------------------------------------------------------------------------------------------
    | 写日志警告信息
    -------------------------------------------------------------------------------------------*/
    public static function warning($msg, $module = '')
    {
        if (empty($msg)) return;

        self::getInstance();

        $time = date('Y-m-d H:i:s');
        $msg = str_replace(array("\n","\t"),array("",""),$msg);
        $module = empty($module) ? self::$_defaultModule : str_replace(["\n", "\t"], ["", ""], $module);

        $logLine = "[$time][" . self::WARNING . "][$module]:$msg\r\n";
        fwrite(self::$_logHandle, $logLine);
    }

    /*------------------------------------------------------------------------------------------
    | 写日志错误信息
    -------------------------------------------------------------------------------------------*/
    public static function error($msg, $module = '')
    {
        if (empty($msg)) return;

        self::getInstance();

        $time = date('Y-m-d H:i:s');
        $msg = str_replace(array("\n","\t"),array("",""),$msg);
        $module = empty($module) ? self::$_defaultModule : str_replace(["\n", "\t"], ["", ""], $module);

        $logLine = "[$time][" . self::ERROR . "][$module]:$msg\r\n";
        fwrite(self::$_logHandle, $logLine);
    }

    /*------------------------------------------------------------------------------------------
    | 写日志严重错误信息
    -------------------------------------------------------------------------------------------*/
    public static function critical($msg, $module = '')
    {
        if (empty($msg)) return;

        self::getInstance();

        $time = date('Y-m-d H:i:s');
        $msg = str_replace(array("\n","\t"),array("",""),$msg);
        $module = empty($module) ? self::$_defaultModule : str_replace(["\n", "\t"], ["", ""], $module);

        $logLine = "[$time][" . self::CRITICAL . "][$module]:$msg\r\n";
        fwrite(self::$_logHandle, $logLine);
    }

}

?>
