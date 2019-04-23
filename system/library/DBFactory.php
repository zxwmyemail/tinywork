<?php
namespace app\system\library;
/*********************************************************************************************
 * 数据库工厂
 * @copyright   Copyright(c) 2015
 * @author      iProg
 * @version     1.0
 ********************************************************************************************/

class DBFactory {

    private $_DBConfig = null;
    private $_whichDB  = null;

    function __construct($DBConfig, $whichDB='master') 
    {
        $this->_DBConfig = $DBConfig[$whichDB];
        $this->_whichDB  = $whichDB;
    }

    public function __get($dbName='mysql') 
    {
        switch ($dbName) {      	
            case 'mysql' :
            	$mysql = new MySQL();
            	$mysql->init(
            		$this->_DBConfig['db_host'],
            		$this->_DBConfig['db_user'],
            		$this->_DBConfig['db_password'],
            		$this->_DBConfig['db_database'],
            		$this->_DBConfig['db_charset'],
            		$this->_DBConfig['db_conn']
            	);
            	return $mysql;
                break;
            case 'mysqlPDO' :
                $DB_DNS  = 'mysql:host='.$this->_DBConfig['db_host'];
                $DB_DNS .= ';port=' . $this->_DBConfig['db_port'];
                $DB_DNS .= ';dbname=' . $this->_DBConfig['db_database'];
                $pdoConfig = array( 
                    'username'  => $this->_DBConfig['db_user'], 
                    'password'  => $this->_DBConfig['db_password'],
                    'dbcharset' => $this->_DBConfig['db_charset'],
                    'pconnect'  => $this->_DBConfig['db_conn'],   //是否永久链接，0非永久，1永久
                    'dns'       => $DB_DNS
                );
                return BasePDO::getInstance($pdoConfig, $this->_whichDB);
                break;
            default :
                # code
                break;
        }
    }
}
?>
