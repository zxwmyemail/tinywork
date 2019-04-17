<?php /* Smarty version Smarty-3.1.21-dev, created on 2019-04-16 15:31:19
         compiled from "D:\phpStudy\PHPTutorial\WWW\zfw\mvc\view\homeModule\home\probe.html" */ ?>
<?php /*%%SmartyHeaderCode:2589279875cb4578ba8d886-37177035%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c9161d5eb16edf205db5908061f01a3438fbfe0e' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\zfw\\mvc\\view\\homeModule\\home\\probe.html',
      1 => 1555399878,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2589279875cb4578ba8d886-37177035',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5cb4578babfec8_27327618',
  'variables' => 
  array (
    'serverParam' => 0,
    'phpParam' => 0,
    'pluginParam' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5cb4578babfec8_27327618')) {function content_5cb4578babfec8_27327618($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
    <title>ZxwFramework 探针</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        * {font-family: Tahoma, "Microsoft Yahei", Arial; }
        body{text-align: center; margin: 0 auto; padding: 0 0 10px 0; background-color:#FFFFFF;font-size:12px;font-family:Tahoma, Arial}
        h1 {font-size: 24px; font-weight: bold; padding: 0; margin: 0; color: #444444;}
        a{color: blue; text-decoration:none;}
        table{clear:both;padding: 0; margin: 0 0 10px;border-collapse:collapse; border-spacing: 0;}
        th{padding: 5px 6px; font-weight:bold;background:#008B00;color:#FFFFFF;border:1px solid #008B00; text-align:left;}
        tr{padding: 0; background:#F7F7F7;}
        td{padding: 3px 6px; border:1px solid #CCCCCC;}
        #page {width: 98%;  margin: 0 auto; text-align: left;}
        #header{position: relative; padding: 10px;}
        .word{word-break:break-all;}
    </style>
</head>
<body>

<div id="page">
    <div id="header">
        <h1>): 欢迎使用ZxwFramework</h1>
    </div>

    <table width="100%" cellpadding="3" cellspacing="0">
        <tr><th colspan="4">服务器参数</th></tr>
        <tr>
            <td width="12%">服务器域名/IP地址</td>
            <td width="38%"><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_domain'];?>
</td>
            <td width="12%">服务器端口</td>
            <td width="38%"><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_port'];?>
</td>
        </tr>
        <tr>
            <td>服务器操作系统</td>
            <td><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_flag'];?>
</td>
            <td>服务器解译引擎</td>
            <td><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_engine'];?>
</td>
        </tr>
        <tr>
            <td>服务器主机名</td>
            <td colspan="3"><?php echo $_smarty_tpl->tpl_vars['serverParam']->value['server_hostname'];?>
</td>
        </tr>
    </table>

    <table width="100%" cellpadding="3" cellspacing="0" align="center">
        <tr><th colspan="4">PHP相关参数</th></tr>
        <tr>
            <td width="24%">PHP信息（phpinfo）：</td>
            <td width="26%"><a href="index.php?r=home.getPhpInfo" target="_blank">phpinfo信息</a></td>
            <td width="24%">PHP版本（php_version）：</td>
            <td width="26%"><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_version'];?>
</td>
        </tr>
        <tr>
            <td>PHP运行方式</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_sapi_name'];?>
</td>
            <td>脚本占用最大内存（memory_limit）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_memory_limit'];?>
</td>
        </tr>
        <tr>
            <td>PHP安全模式（safe_mode）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_safe_mode'];?>
</td>
            <td>POST方法提交最大限制（post_max_size）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_post_max_size'];?>
</td>
        </tr>
        <tr>
            <td>上传文件最大限制（upload_max_filesize）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_upload_max_filesize'];?>
</td>
            <td>浮点型数据显示的有效位数（precision）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_precision'];?>
</td>
        </tr>
        <tr>
            <td>脚本超时时间（max_execution_time）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_max_execution_time'];?>
秒</td>
            <td>socket超时时间（default_socket_timeout）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_default_socket_timeout'];?>
秒</td>
        </tr>
        <tr>
            <td>PHP页面根目录（doc_root）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_doc_root'];?>
</td>
            <td>用户根目录（user_dir）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_user_dir'];?>
</td>
        </tr>
        <tr>
            <td>dl()函数（enable_dl）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_enable_dl'];?>
</td>
            <td>指定包含文件目录（include_path）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_include_path'];?>
</td>
        </tr>
        <tr>
            <td>显示错误信息（display_errors）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_display_errors'];?>
</td>
            <td>自定义全局变量（register_globals）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_register_globals'];?>
</td>
        </tr>
        <tr>
            <td>数据反斜杠转义（magic_quotes_gpc）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_magic_quotes_gpc'];?>
</td>
            <td>"&lt;?...?&gt;"短标签（short_open_tag）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_short_open_tag'];?>
</td>
        </tr>
        <tr>
            <td>"&lt;% %&gt;"ASP风格标记（asp_tags）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_asp_tags'];?>
</td>
            <td>忽略重复错误信息（ignore_repeated_errors）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_ignore_repeated_errors'];?>
</td>
        </tr>
        <tr>
            <td>忽略重复的错误源（ignore_repeated_source）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_ignore_repeated_source'];?>
</td>
            <td>报告内存泄漏（report_memleaks）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_report_memleaks'];?>
</td>
        </tr>
        <tr>
            <td>自动字符串转义（magic_quotes_gpc）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_magic_quotes_gpc'];?>
</td>
            <td>外部字符串自动转义（magic_quotes_runtime）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_magic_quotes_runtime'];?>
</td>
        </tr>
        <tr>
            <td>打开远程文件（allow_url_fopen）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_allow_url_fopen'];?>
</td>
            <td>声明argv和argc变量（register_argc_argv）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_register_argc_argv'];?>
</td>
        </tr>
        <tr>
            <td>Cookie 支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_cookie'];?>
</td>
            <td>拼写检查（ASpell Library）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_aspell_check_raw'];?>
</td>
        </tr>
        <tr>
            <td>高精度数学运算（BCMath）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_bcadd'];?>
</td>
            <td>PREL相容语法（PCRE）</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_preg_match'];?>
</td>
        </tr> 
        <tr>
            <td>PDF文档支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_pdf_close'];?>
</td>
            <td>SNMP网络管理协议</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_snmpget'];?>
</td>
        </tr> 
        <tr>
            <td>VMailMgr邮件处理</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_vm_adduser'];?>
</td>
            <td>Curl支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_curl_init'];?>
</td>
        </tr> 
        <tr>
            <td>SMTP支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_smtp'];?>
</td>
            <td>SMTP地址</td>
            <td><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_smtp_addr'];?>
</td>
        </tr> 
    	<tr>
        	<td>默认支持函数（enable_functions）</td>
        	<td colspan="3"><a href='index.php?r=home.getEnableFun' target='_blank'>请点这里查看详细！</a></td>		
    	</tr>
    	<tr>
    		<td>被禁用的函数（disable_functions）</td>
    		<td colspan="3" class="word"><?php echo $_smarty_tpl->tpl_vars['phpParam']->value['php_disable_functions'];?>
</td>
    	</tr>
    </table>

    <!--组件信息-->
    <table width="100%" cellpadding="3" cellspacing="0" align="center">
        <tr><th colspan="4" >组件支持</th></tr>
        <tr>
            <td width="24%">Zend版本</td>
            <td width="26%"><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_zend_version'];?>
</td>
            <td width="24%">LDAP目录协议：</td>
            <td width="26%"><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_ldap_close'];?>
</td>
        </tr>
        <tr>
            <td>eAccelerator</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_eAccelerator'];?>
</td>
            <td>ioncube</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_ioncube'];?>
</td>
        </tr>
        <tr>
            <td>XCache</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_XCache'];?>
</td>
            <td>APC</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_APC'];?>
</td>
        </tr>
        <tr>
            <td>FTP支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_ftp_login'];?>
</td>
            <td>XML解析支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_xml_set_object'];?>
</td>
        </tr>
        <tr>
            <td>Session支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_session_start'];?>
</td>
            <td>Socket支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_socket_accept'];?>
</td>
        </tr>
        <tr>
            <td>Calendar支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_cal_days_in_month'];?>
</td>
            <td>允许URL打开文件</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_allow_url_fopen'];?>
</td>
        </tr>
        <tr>
            <td>GD库支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_gd'];?>
</td>
            <td>压缩文件支持(Zlib)</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_gzclose'];?>
</td>
        </tr>
        <tr>
            <td>IMAP电子邮件系统函数库</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_imap_close'];?>
</td>
            <td>历法运算函数库</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_JDToGregorian'];?>
</td>
        </tr>
        <tr>
            <td>正则表达式函数库</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_preg_match'];?>
</td>
            <td>WDDX支持</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_wddx_add_vars'];?>
</td>
        </tr>
        <tr>
            <td>Iconv编码转换</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_iconv'];?>
</td>
            <td>mbstring</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_mb_eregi'];?>
</td>
        </tr>
        <tr>
            <td>MCrypt加密处理</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_mcrypt_cbc'];?>
</td>
            <td>哈稀计算</td>
            <td><?php echo $_smarty_tpl->tpl_vars['pluginParam']->value['plugin_mhash_count'];?>
</td>
        </tr>
    </table>
</div>
</body>
</html>
<?php }} ?>
