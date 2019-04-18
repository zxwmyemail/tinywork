<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-04-18 11:22:10
         compiled from "D:\phpStudy\PHPTutorial\WWW\tinywork\mvc\view\homeModule\home\probe.html" */ ?>
<?php /*%%SmartyHeaderCode:6816900415cb6ee9f326c63-17356679%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '18fec16392c07f8825a1f208ed2fd19d86c5b687' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\tinywork\\mvc\\view\\homeModule\\home\\probe.html',
      1 => 1555557727,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6816900415cb6ee9f326c63-17356679',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5cb6ee9f354495_24651479',
  'variables' => 
  array (
    'serverParam' => 0,
    'phpParam' => 0,
    'pluginParam' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cb6ee9f354495_24651479')) {function content_5cb6ee9f354495_24651479($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>Tinywork 探针</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="asset/css/probe.css">  
</head>
<body>
    <div id="page">
        <div id="header">
            <h1>): 欢迎使用 Tinywork</h1>
        </div>

        <table width="100%" cellpadding="3" cellspacing="0">
            <tr><th colspan="4">服务器参数</th></tr>
            <tr>
                <td width="18%" class="title">服务器域名/IP地址</td>
                <td width="32%"><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_domain'];?>
</td>
                <td width="18%" class="title">服务器端口</td>
                <td width="32%"><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_port'];?>
</td>
            </tr>
            <tr>
                <td class="title">服务器操作系统</td>
                <td><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_flag'];?>
</td>
                <td class="title">服务器uname</td>
                <td><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_operate_system'];?>
</td>
            </tr>
            <tr>
                <td class="title">服务器解译引擎</td>
                <td><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_engine'];?>
</td>
                <td class="title">服务器主机名</td>
                <td><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_hostname'];?>
</td>
            </tr>
        </table>

        <table width="100%" cellpadding="3" cellspacing="0" align="center">
            <tr><th colspan="4">PHP主要的相关参数和组件支持</th></tr>
            <tr>
                <td width="18%" class="title">PHP信息（phpinfo）：</td>
                <td width="32%"><a href="index.php?r=home.getPhpInfo" target="_blank">phpinfo信息</a></td>
                <td width="18%" class="title">PHP版本（php_version）：</td>
                <td width="32%"><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_version'];?>
</td>
            </tr>
            <tr>
                <td class="title">PHP运行方式</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_sapi_name'];?>
</td>
                <td class="title">PHP安全模式（safe_mode）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_safe_mode'];?>
</td>
            </tr>
            <tr>
                <td class="title">Zend版本</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['plugin_zend_version'];?>
</td>
                <td class="title">脚本占用最大内存（memory_limit）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_memory_limit'];?>
</td>
            </tr>
            <tr>
                <td class="title">POST方法提交最大限制（post_max_size）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_post_max_size'];?>
</td>
                <td class="title">允许URL打开文件</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['plugin_allow_url_fopen'];?>
</td>
            </tr>
            <tr>
                <td class="title">上传文件最大限制（upload_max_filesize）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_upload_max_filesize'];?>
</td>
                <td class="title">浮点型数据显示的有效位数（precision）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_precision'];?>
</td>
            </tr>
            <tr>
                <td class="title">脚本超时时间（max_execution_time）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_max_execution_time'];?>
秒</td>
                <td class="title">socket超时时间（default_socket_timeout）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_default_socket_timeout'];?>
秒</td>
            </tr>
            <tr>
                <td class="title">显示错误信息（display_errors）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_display_errors'];?>
</td>
                <td class="title">自定义全局变量（register_globals）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_register_globals'];?>
</td>
            </tr>
            <tr>
                <td class="title">数据反斜杠转义（magic_quotes_gpc）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_magic_quotes_gpc'];?>
</td>
                <td class="title">"&lt;?...?&gt;"短标签（short_open_tag）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_short_open_tag'];?>
</td>
            </tr>
            <tr>
                <td class="title">高精度数学运算（BCMath）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_bcadd'];?>
</td>
                <td class="title">报告内存泄漏（report_memleaks）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_report_memleaks'];?>
</td>
            </tr>
            <tr>
                <td class="title">自动字符串转义（magic_quotes_gpc）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_magic_quotes_gpc'];?>
</td>
                <td class="title">外部字符串自动转义（magic_quotes_runtime）</td>
                <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_magic_quotes_runtime'];?>
</td>
            </tr> 
        	<tr>
            	<td class="title">默认支持函数（enable_functions）</td>
            	<td><a href='index.php?r=home.getEnableFun' target='_blank'>请点这里查看详细！</a></td>
                <td class="title">被禁用的函数（disable_functions）</td>
                <td class="word"><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_disable_functions'];?>
</td>		
        	</tr>
            <tr>
                <td class="title">历法运算函数库</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_JDToGregorian'];?>
</td>
                <td class="title">正则表达式函数库</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_preg_match'];?>
</td>
            </tr>
            <tr>
                <td class="title">eAccelerator</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_eAccelerator'];?>
</td>
                <td class="title">XCache</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_XCache'];?>
</td>
            </tr>
            <tr>
                <td class="title">Socket支持</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_socket_accept'];?>
</td>
                <td class="title">Curl支持</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['php_curl_init'];?>
</td>
            </tr>
            <tr>
                <td class="title">FTP支持</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_ftp_login'];?>
</td>
                <td class="title">XML解析支持</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_xml_set_object'];?>
</td>
            </tr>
            <tr>
                <td class="title">Cookie 支持</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['php_cookie'];?>
</td>
                <td class="title">Session支持</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_session_start'];?>
</td>
            </tr>
            <tr>
                <td class="title">GD库支持</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_gd'];?>
</td>
                <td class="title">压缩文件支持(Zlib)</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_gzclose'];?>
</td>
            </tr>
            <tr>
                <td class="title">IMAP电子邮件系统函数库</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_imap_close'];?>
</td>
                <td class="title">SMTP支持</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['php_smtp'];?>
</td>
            </tr>
            <tr>
                <td class="title">Iconv编码转换</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_iconv'];?>
</td>
                <td class="title">mbstring</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_mb_eregi'];?>
</td>
            </tr>
            <tr>
                <td class="title">MCrypt加密处理</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_mcrypt_cbc'];?>
</td>
                <td class="title">哈稀计算</td>
                <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_mhash_count'];?>
</td>
            </tr>
        </table>
    </div>
</body>
</html>
<?php }} ?>
