<?php
namespace app\mvc\home\controllers;

use app\system\Application;
use app\system\core\Controller;
use app\system\library\BaseLog;
use app\system\library\Util;
use app\mvc\home\models\Test;
use app\mvc\home\models\Probe;

class Home extends Controller {
        
    public function __construct() {
        parent::__construct();
    }

    public function page500() {
        $this->smarty->display('page500.html'); 
    }

    public function test() {
        
        //测试重定向
        // $this->redirect('page404'); 

        //测试model类
        $test = new Test();
        // $test->testPDO();
        // $test->testRedis();
        $test->testExcel();
    }

    public function testLog() {
        
        BaseLog::error('测试日志');
    }
    
    public function index() {
        $probe = new Probe();
        $serverParam = $probe->getServerParam();
        $phpParam    = $probe->getPhpParam();
        $pluginParam = $probe->getPluginParam();

        $this->smarty->assign('serverParam', $serverParam);
        $this->smarty->assign('phpParam', $phpParam);
        $this->smarty->assign('pluginParam', $pluginParam);
        $this->smarty->display('probe.html'); 
    }

    public function getPhpInfo() {
        phpinfo();
    }

    public function getEnableFun() {
        $arr = get_defined_functions();
        Function php(){};
        echo "<p>这里显示系统支持的所有函数和自定义函数</p>";
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
}

