<?php
namespace app\system\core;
/********************************************************************************************
 * 核心控制器父类
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 ********************************************************************************************/
use app\system\Application;
use app\system\library\CacheFactory;
use app\extend\ZFSmarty;

class Controller {

    private $_redisConfig  = null;
    private $_memcacheConfig  = null;
    private $_smarty = null;
    private $_jsonRPCClient = null;
        
    public function __construct() {
        $this->setRedisConfig();
        $this->setMemcacheConfig();
    }
    
    /*---------------------------------------------------------------------------------------
    | 返回接口json数据，进行gzip压缩
    ----------------------------------------------------------------------------------------*/
    public function renderJSON($data) {
        ob_end_clean();

        // 启用gzip压缩输出
        if(function_exists('ob_gzhandler')) {
            ob_start('ob_gzhandler');
        } else {
            ob_start();
        }
        
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        // echo json_encode($data);

        ob_flush();
        flush();
        die(0);
    }
    
    /*---------------------------------------------------------------------------------------
    | 直接使用php嵌入html的方式渲染模板
    ----------------------------------------------------------------------------------------*/
    public function display($htmlFile, $data = []) {
        $routeInfo = Application::getMCA();

        $_module     = $routeInfo['module'].'Module';
        $_controller = $routeInfo['controller'];
        
        //设置各个目录的路径，这里是配置smarty的路径参数
        $templatePath = VIEW_PATH . '/' . $_module . '/' . $_controller . '/' . $htmlFile . '.php';

        extract($data); 
        include($templatePath);
        die(0);
    }
	
    /*---------------------------------------------------------------------------------------
    | 获取smarty实例
    |---------------------------------------------------------------------------------------*/
    public function __get($name = ''){
        switch ($name) {
            case 'smarty':
                if (empty($this->_smarty)) {
                    $zfSmarty = new ZFSmarty();
                    $this->_smarty = $zfSmarty->getSmarty(); 
                }
                return $this->_smarty;
                break;    
            default:
                echo '获取' . $name . '实例时，参数错误';exit();
                break;
        }
    }
    
    /*---------------------------------------------------------------------------------------
    | 获取缓存实例
    |----------------------------------------------------------------------------------------
    | @access  final   public
    | @param   string  $name    缓存名字：session、redis、hashRedis、memcache
    | @param   string  $whichCache    哪一个缓存：
    |                                 2、如果获取redis的master实例：
    |                                    $whichCache = 'master';
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
    
    /*---------------------------------------------------------------------------------------
    | 设置客户端无缓存
    ----------------------------------------------------------------------------------------*/
    public function setNoCache()
    { 
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	    header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0",false);
	    header("Pragma: no-cache");
    }

    /*-------------------------------------------------------------------------------------------------------------------------
     | 客户端缓存控制函数 
     |-------------------------------------------------------------------------------------------------------------------------
     | $type  缓存类型 
     | $interval  客户端缓存过期时间 
     | $mktime  设置Last-Modified 
     | $etag  设置ETag标志 
     -------------------------------------------------------------------------------------------------------------------------*/    
    public function  http_cache_control($type = 'nocache', $interval = 0, $mktime = '', $etag = '') {       
        if ($type == 'nocache') {       
           header('Expires: -1');  //设置 -1为立刻过期      
           header('Pragma: no-cache');       
           header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');       
        } else {   //检查  ETag: 值$_SERVER['HTTP_IF_NONE_MATCH']  
            if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $etag && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {       
                header('HTTP/1.1 304 Not Modfied');       
            } elseif (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $mktime && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == gmdate ('r', $mktime).' GMT') { header('HTTP/1.1 304 Not Modfied');  //检查 Last-Modified: 值 $_SERVER['HTTP_IF_MODIFIED_SINCE']      
            } else {     //根据修改时间加过期时间，算出过期时间点  
                if ($mktime) {       
                   $gmtime = gmdate ('r' , $mktime + $interval). ' GMT' ;       
                   header('Expires: ' . $gmtime);       
                }       
                if ($type == 'public') {       
                   header('Cache-Control: public,max-age=' . $interval);       
                } elseif ($type == 'private') {       
                   header('Cache-Control: private,max-age=' . $interval . ',s-maxage=0');       
                } elseif ($type == 'none') {       
                   header('Cache-Control: must-revalidate,proxy-revalidate');       
                }       
            }       
            $mktime && header('Last-Modified: ' . gmdate ('r' , $mktime) . ' GMT');       
            $etag   && header('ETag: ' . $etag);       
        }       
    }
    
    /*-------------------------------------------------------------------------------------
    | 重定向
    |--------------------------------------------------------------------------------------
    | @param  $action     string   重定向路由，格式有两种：
    |                              1. 'controller/action',重定向到其他控制层
    |                              2. 'action',重定向到自身控制层的其他action
    | @param  $param      array    重定向参数
    | @param  $end        bool     重定向后是否终止应用
    | @param  $statusCode int      重定向http请求状态码值，默认302，即重定向
    --------------------------------------------------------------------------------------*/
    public function redirect($action, $param = [], $end = true, $statusCode = 302) {
        if (empty($action)) return false;
	
        $action = explode('/', $action);
        $reqParams = Application::$_reqParams;
        $defaultRoute = $this->config('route');
        $url = empty($reqParams['module']) ? 'index.php?' : 'index.php?m='.$reqParams['module'].'&';

        if ($defaultRoute['url_type'] == 1) {
            
            if (count($action) == 2) {
                $url .= 'r='.$action[0] . '.' . $action[1];
            } elseif (count($action) == 1) {
                $url .= empty($reqParams['controller']) ? 'r='.$defaultRoute['default_controller'].'.' : 'r='.$reqParams['controller'].'.';
                $url .= $action[0];
            } else {
                trigger_error('重定向失败，路由参数【 '.$action.' 】解析失败！');die();
            }

            if (!empty($param)) {
                $url .= "&" . http_build_query($param);
            }
        } elseif ($defaultRoute['url_type'] == 2) {
            $url = empty($reqParams['module']) ? 'index.php/' : 'index.php/'.$reqParams['module'].'/';
            if (count($action) == 2) {
                $url .= $action[0].'/'.$action[1];
            } elseif (count($action) == 1) {
                $url .= empty($reqParams['controller']) ? $defaultRoute['default_controller'].'/' : $reqParams['controller'].'/';
                $url .= $action[0];
            } else {
                trigger_error('重定向失败，路由参数【 '.$action.' 】解析失败！');die();
            }

            if (!empty($param)) {
                $url .= '/?'.http_build_query($param);
            }
        }

        header("Location:".$url, true, $statusCode);

        if ($end) exit();
    }

    /*--------------------------------------------------------------------------------------
    | 加载系统配置,默认为系统配置 $CONFIG['system'][$config]
    |---------------------------------------------------------------------------------------
    | @access      final   protected
    | @param       string  $config 配置名  
    --------------------------------------------------------------------------------------*/
    protected function config($config) {
        return Application::$_config[$config];
    }

    /*--------------------------------------------------------------------------------------
    | 加载redis参数配置
    --------------------------------------------------------------------------------------*/
    protected function setRedisConfig() {
        $this->_redisConfig = Application::$_config['redis'];
    }

    /*--------------------------------------------------------------------------------------
    | 加载memcache参数配置
    --------------------------------------------------------------------------------------*/
    protected function setMemcacheConfig() {
        $this->_memcacheConfig = Application::$_config['memcache'];
    }


}


