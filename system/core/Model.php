<?php
/*******************************************************************************************
 * 核心model层父类
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 *******************************************************************************************/
namespace app\system\core;

use app\system\Application;
use app\system\library\CacheFactory;
use app\system\library\DBFactory;

class Model {

    protected $_mysqlConfig    = null;
    protected $_redisConfig    = null;
    protected $_memcacheConfig = null;

    public function __construct() {
        $this->setDbConfig();
        $this->setRedisConfig(); 
        $this->setMemcacheConfig();              
    }

    /*--------------------------------------------------------------------------------------
    | 获取数据库实例
    |---------------------------------------------------------------------------------------
    | @access  final   public
    | @param   string  $name    数据库名字：mysql 、mysqlPDO
    | @param   string  $whichDB  哪一个配置, 对于配置文件params.config.php文件中:
    |                            如果想获取mysql的master数据库连接实例，则$whichDB = master
    --------------------------------------------------------------------------------------*/
    public function getDB($name = 'mysqlPDO', $whichDB = 'master'){
        switch ($name) {
            case 'mysql':
                $DB = new DBFactory($this->_mysqlConfig, $whichDB);
                return  $DB->mysql;
                break;
            case 'mysqlPDO':
                $DB = new DBFactory($this->_mysqlConfig, $whichDB);
                return  $DB->mysqlPDO;
                break; 
            default:
                echo '参数错误';exit();
                break;
            }
    }
    
    /*---------------------------------------------------------------------------------------
    | 获取缓存实例
    |----------------------------------------------------------------------------------------
    | @access  final   public
    | @param   string  $name    缓存名字：session、redis、hashRedis、memcache
    | @param   string  $whichCache    哪一个缓存：
    |                                 如果获取redis的master实例：$whichCache = 'master';
    ----------------------------------------------------------------------------------------*/
    public function getCache($name = 'session', $whichCache = 'master'){
        switch ($name) {
            case 'session':
                $cache = new CacheFactory();
                return  $cache->session;
                break;
            case 'redis':
                $cache = new CacheFactory($this->_redisConfig, $whichCache);
                return  $cache->redis;
                break;
            case 'hashRedis':
                $cache = new CacheFactory($this->_redisConfig);
                return  $cache->hashRedis;
                break;
            case 'memcache':
                $cache = new CacheFactory($this->_memcacheConfig);
                return  $cache->memcache;
                break;
            default:
                echo '参数错误';exit();
                break;
        }
    }

    /*-----------------------------------------------------------------------------------
    | 根据表前缀获取表名
    |------------------------------------------------------------------------------------
    | @access      final   protected
    | @param       string  $table_name    表名
    -----------------------------------------------------------------------------------*/
    protected function table($table_name){
        $config_db = $this->config('db');
        return $config_db['db_table_prefix'] . $table_name;
    }

    /*-----------------------------------------------------------------------------------
    | 加载系统配置,默认为系统配置 $CONFIG['system'][$config]
    |------------------------------------------------------------------------------------
    | @access      final   protected
    | @param       string  $config 配置名  
    -----------------------------------------------------------------------------------*/
    protected function config($config){
        return Application::$_config[$config];
    }

    /*-----------------------------------------------------------------------------------
    | 加载数据库DB参数配置
    -----------------------------------------------------------------------------------*/
    protected function setDbConfig(){
        $this->_mysqlConfig  = Application::$_config['mysql'];
    }

    /*-----------------------------------------------------------------------------------
    | 加载redis参数配置
    -----------------------------------------------------------------------------------*/
    protected function setRedisConfig(){
        $this->_redisConfig = Application::$_config['redis'];
    }

    /*-----------------------------------------------------------------------------------
    | 加载memcache参数配置
    -----------------------------------------------------------------------------------*/
    protected function setMemcacheConfig(){
        $this->_memcacheConfig = Application::$_config['memcache'];
    }
        
}

?>


