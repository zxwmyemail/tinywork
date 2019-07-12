<?php
/*******************************************************************************************
 * 公用方法类
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 *******************************************************************************************/
namespace app\system\core;

use app\system\library\BaseSession;
use app\system\library\BaseRedis;
use app\system\library\HashRedis;
use app\system\library\BaseMemcache;
use app\system\library\BasePDO;

trait Common {

    private $_hashRedis;
    private $_memcache;

    /*--------------------------------------------------------------------------------------
    | 获取数据库实例
    |---------------------------------------------------------------------------------------
    | @access  final   public
    | @param   string  $name    数据库名字：mysql
    | @param   string  $whichDB  哪一个配置, 对于配置文件params.config.php文件中:
    |                            如果想获取mysql的master数据库连接实例，则$whichDB = master
    --------------------------------------------------------------------------------------*/
    public function getDB($name = 'mysql', $whichDB = 'master'){
        switch ($name) {
            case 'mysql':
                $conf = Config::get('database', $whichDB);

                $DB_DNS  = 'mysql:host=' . $conf['db_host'];
                $DB_DNS .= ';port=' . $conf['db_port'];
                $DB_DNS .= ';dbname=' . $conf['db_database'];
                $pdoConfig = array( 
                    'username'  => $conf['db_user'], 
                    'password'  => $conf['db_password'],
                    'dbcharset' => $conf['db_charset'],
                    'pconnect'  => $conf['db_conn'],     //是否永久链接，0非永久，1永久
                    'dns'       => $DB_DNS
                );
                return BasePDO::getInstance($pdoConfig, $whichDB);
                break; 
            default:
                echo '数据库参数错误';exit();
                break;
        }
    }
    
    /*---------------------------------------------------------------------------------------
    | 获取缓存实例
    |----------------------------------------------------------------------------------------
    | @access  final   public
    | @param   string  $name          缓存名字：session、redis、hashRedis
    | @param   string  $whichCache    哪一个缓存：
    |                                 如果获取redis的master实例：$whichCache = 'master';
    ----------------------------------------------------------------------------------------*/
    public function getCache($name = 'session', $whichCache = 'master'){
        switch ($name) {
            case 'session':
                return new BaseSession();
                break;
            case 'redis':
                $redisConf = Config::get('redis', $whichCache);
                return BaseRedis::getInstance($redisConf, $whichCache);
                break;
            case 'hashRedis':
                if (empty($this->_hashRedis)) {
                    $hashRedisConf = Config::get('hashRedis');
                    $this->_hashRedis = new HashRedis($hashRedisConf);
                }
                return $this->_hashRedis;
                break;
            case 'memcache':
                if (empty($this->_memcache)) {
                    $_memcacheConf = Config::get('memcache');
                    $this->_memcache = new BaseMemcache($_memcacheConf);
                }
                return $this->_memcache;
                break;
            default:
                echo '参数错误';exit();
                break;
        }
    }
        
}

?>


