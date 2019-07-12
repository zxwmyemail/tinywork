<?php
namespace app\mvc\home\models;

use app\system\core\Model;
use app\extend\ZFExcel;

class Test extends Model{

    public function test(){
        echo "this is test homeModel";
    }
    
    public function testPDO(){
        var_dump($this->getDB('mysql'));
    }

    public function testRedis(){
        var_dump($this->getCache('redis'));die();
    }

    public function testExcel(){
    	$zfExcel = new ZFExcel();
        $fields = ['按钮编号', '按钮名称', '按钮点击次数', '回链点击次数', '奖励点击比'];
        $zfExcel->exportExcel($fields, '', 'game_btn_state', 'ello-game');
    }
}


