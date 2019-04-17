<?php
namespace app\mvc\controller\homeModule;

use app\system\Application;
use app\system\core\Controller;
use app\system\library\Log;
use app\system\library\Util;
use app\mvc\model\Test;
use app\mvc\model\Probe;

class homeController extends Controller {
        
    public function __construct() {
        parent::__construct();
    }

    public function page404Action() {
        $this->smarty->display('404Page.html'); 
    }

    public function testAction() {
        
        //测试重定向
        $this->redirect('page404'); 

        //测试model类
        $test = new Test();
        $test->testPDO();
        $test->testRedis();
        $test->testExcel();
    }
    
    public function indexAction() {
        $probe = new Probe();
        $serverParam = $probe->getServerParam();
        $phpParam    = $probe->getPhpParam();
        $pluginParam = $probe->getPluginParam();

        $this->smarty->assign('serverParam', $serverParam);
        $this->smarty->assign('phpParam', $phpParam);
        $this->smarty->assign('pluginParam', $pluginParam);
        $this->smarty->display('probe.html'); 
    }

    public function getPhpInfoAction() {
        phpinfo();
    }

    public function getEnableFunAction() {
        $arr = get_defined_functions();
        Function php(){};
        echo "<p>这里显示系统支持的所有函数和自定义函数</p>";
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
}

