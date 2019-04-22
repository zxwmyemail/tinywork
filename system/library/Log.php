<?php
namespace app\system\library;
/********************************************************************************************
  PHP Log 类 

  使用方法：
    $logIns = Log::getInstance();
    $logIns->logMessage("test",Log::INFO,'myTest'); 
********************************************************************************************/

class Log {
    
    private static $_logInstance = null;
    private $LogFile;

    const DEBUG    = 'DEBUG';       //调试bug级别
    const INFO     = 'INFO';        //信息提示级别
    const NOTICE   = 'NOTICE';      //通知级别
    const WARNING  = 'WARNING';     //警告级别
    const ERROR    = 'ERROR';       //错误级别
    const CRITICAL = 'CRITICAL';    //严重级别
    
    private function __construct() {
        $logDir = LOG_PATH.'/app_log/' . date('Y') . '/';
        if (!is_dir($logDir)) 
            mkdir($logDir, 0777, true); 

        $logFile = $logDir . date('Y-m-d') . '.txt';
        $this->LogFile = @fopen($logFile,'a+');

        if (!is_writable($logFile)) {
            chmod($logFile, 0777);
        } 
       
        if(!is_resource($this->LogFile)){
            throw new \Exception('无效的文件路径');
        }
    }

    /*------------------------------------------------------------------------------------------
    | 析构函数，删除文件资源句柄
    -------------------------------------------------------------------------------------------*/
    public function __destruct()
    {  
        // 释放句柄资源
        if(is_resource($this->LogFile)){
            fclose($this->LogFile); 
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
    | 写日志信息
    -------------------------------------------------------------------------------------------*/
    public function logMessage($msg, $logLevel = Log::INFO, $module = 'SYSTEM')
    {
        $msg = trim($msg);

        if (empty($msg)) {
            return;
        }

        $time = date('Y-m-d H:i:s');
        $msg = str_replace(array("\n","\t"),array("",""),$msg);
        $module = str_replace(array("\n","\t"),array("",""),$module);

        $logLine = "[$time][$logLevel][$module]:$msg\r\n";
        fwrite($this->LogFile,$logLine);
    }

}

?>
