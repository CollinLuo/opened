<?php
// +----------------------------------------------------------------------
// | TryDemo [ This is just a demo! ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://www.trydemo.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: lsq <qiangshiluo@126.com> 2013-12-11
// +----------------------------------------------------------------------

//前后台通用配置文件 
$config1 = array(

	/* 项目设定 */
    //'APP_AUTOLOAD_PATH' => '@.TagLib', // 自动加载机制的自动搜索路径,注意搜索顺序
	/* 日志设置 */
    'LOG_RECORD' => true, // 开启日志记录
    'LOG_TYPE' => 'File', // 日志记录类型 0 系统 1 邮件 3 文件 4 SAPI 默认为文件方式
    //'LOG_DEST' => '', // 日志记录目标（ThinkPHP3.2.3废除）
    //'LOG_EXTRA' => '', // 日志记录额外信息（ThinkPHP3.2.3废除）
    'LOG_LEVEL' => 'EMERG,ALERT,CRIT,ERR', // 允许记录的日志级别
    'LOG_FILE_SIZE' => 2097152, // 日志文件大小限制
    'LOG_EXCEPTION_RECORD' => false, // 是否记录异常信息日志
    /* 模板引擎设置 */
    'TMPL_CONTENT_TYPE' => 'text/html', // 默认模板输出类型
    'TMPL_ACTION_ERROR' => 'Prompt/jump', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Prompt/jump', // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE' => THINK_PATH . 'Tpl/think_exception.tpl', // 异常页面的模板文件
    'TMPL_FILE_DEPR' => '/',
    'DEFAULT_THEME' => '', //默认的模板主题名
    'TMPL_STRIP_SPACE' => false, //是否去除模板文件里面的html空格与换行
    'TMPL_TEMPLATE_SUFFIX' => '.html', //模板后缀

	/* 允许访问的模块列表 */
	'MULTI_MODULE'=>true,
	'MODULE_ALLOW_LIST' => array('Home','Admin'),
	/* 默认访问模块 */
	'DEFAULT_MODULE'=>'Home',
	/* 设置禁止访问的模块列表 */
	'MODULE_DENY_LIST' => array('Common','Runtime','Api'),
    /* 路由规则配置 */
    'URL_ROUTER_ON' => false, //是否开启路由
    'URL_ROUTE_RULES' => array(),
	
    /* 数据库设置 */
    'DB_TYPE' => 'mysql', // 数据库类型
    'TOKEN_ON' => true, // 是否开启令牌验证
    'TOKEN_NAME' => '__hash__', // 令牌验证的表单隐藏字段名称
    'TOKEN_TYPE' => 'md5', //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET' => FALSE, //令牌验证出错后是否重置令牌 默认为true
    /* 开发人员相关信息 */
    'AUTHOR_INFO' => array(
        'author' => 'lsq',
        'author_email' => 'qiangshiluo@126.com',
    ),
	'TAG_NESTED_LEVEL' => 5, //模版引擎标签多层嵌套等级

	/*邮件发送配置&2014-9-3
	//'配置项'=>'配置值'
    'MAIL_ADDRESS'=>'trydemo@163.com', // 邮箱地址 
    'MAIL_LOGINNAME'=>'trydemo@163.com', // 邮箱登录帐号
    'MAIL_SMTP'=>'smtp.163.com', // 邮箱SMTP服务器
    'MAIL_PASSWORD'=>'', // 邮箱密码
    'SHOW_PAGE_TRACE'=>true,
	*/
	/*邮件发送配置&2015-12-1*/
	'MAIL_HOST' => 'smtp.126.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' => true, //启用smtp认证
    'MAIL_CHARSET' => 'UTF-8',//设置邮件编码
    'MAIL_FROM' =>' trydemo@126.com',//发件人地址
    'MAIL_FROMNAME'=> 'TryDemo',//发件人姓名
	'MAIL_USERNAME' => 'trydemo@126.com',//你的邮箱名
    'MAIL_PASSWORD' => 'lsq9120520',//邮箱密码
	'MAIL_PORT' => 25, //STMP默认端口
	//'MAIL_REPLYTO' => 'trydemo@163.com', //邮件回复地址
	'MAIL_REPLYTO' => 'trydemo@126.com',
    'MAIL_ISHTML' => true, // 是否HTML格式邮件

);

//加载数据库配置
$config2 = APP_PATH . "Common/Conf/systemConfig.php";
$config2 = file_exists($config2) ? include "$config2" : array();
return array_merge($config1, $config2);

?>