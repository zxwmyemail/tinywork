<?php
/*******************************************************************************************
 * URL处理类
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 *******************************************************************************************/
namespace app\system\core;

final class Route {

    private $_queryString;
    private $_urlType;
    private $_reqParams = array();

    public function __construct($urlType = 1) {
        $this->_urlType = $urlType;

        if ($this->_urlType == 1 || $this->_urlType == 2) {
            $this->_queryString = $_SERVER['QUERY_STRING'];
        } else {
            trigger_error("指定的URL模式不存在！");
        }
      
    }


    /*-------------------------------------------------------------------------------------
    | 获取数组形式的URL  
    |--------------------------------------------------------------------------------------
    | @access      public
    -------------------------------------------------------------------------------------*/
    public function getUrlArray()
    {
        switch ($this->_urlType){
            case 1:
                $this->queryToArray();
                break;
            case 2:
                $this->pathinfoToArray();
                break;
        }

        return $this->_reqParams;
    }


    /*-------------------------------------------------------------------------------------
    | 将query形式的URL转化成数组
    |--------------------------------------------------------------------------------------
    | @access      public
    -------------------------------------------------------------------------------------*/
    public function queryToArray()
    {
        $arr = !empty ($this->_queryString) ? explode('&', $this->_queryString ) : array();
        $array = $tmp = array();
        if (count($arr) > 0) {
            foreach ($arr as $item) {
                $tmp = explode('=', $item);
                $array[$tmp[0]] = $tmp[1];
            }
            
            if (isset($array['m'])) {
                $this->_reqParams['module'] = $array['m'];
                unset($array['m']);
            }
            if (isset($array['r'])) {
                $route = explode('.', $array['r']);
                $this->_reqParams['controller'] = $route[0];
                $this->_reqParams['action'] = $route[1];
                unset($array['r']);
            } 
            
            if(count($array) > 0){
                $this->_reqParams['params'] = $array;
            }
        }  
    }


    /*--------------------------------------------------------------------------------------
    | 将PATH_INFO的URL形式转化为数组
    |---------------------------------------------------------------------------------------
    | @access      public
    --------------------------------------------------------------------------------------*/
    public function pathinfoToArray()
    {
        if (isset($_SERVER['PATH_INFO'])){
            //获取 pathinfo
            $pathinfo = trim($_SERVER['PATH_INFO'], '/');
            $pathInfo = explode('/', $pathinfo);

            // 获取 module
            if (count($pathInfo) > 2) {
                $this->_reqParams['module'] = (isset($pathInfo[0]) ? $pathInfo[0] : null);
                array_shift($pathInfo);
            }
            
            // 获取 controller
            $this->_reqParams['controller'] = (isset($pathInfo[0]) ? $pathInfo[0] : null);
            array_shift($pathInfo);

            // 获取 action
            $this->_reqParams['action'] = (isset($pathInfo[0]) ? $pathInfo[0] : null);
            array_shift($pathInfo);
        }

        $this->queryToArray();   
    }

}

